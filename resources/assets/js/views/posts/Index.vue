<template>
  <base-page>
    <sw-page-header :title="$t('posts.title')">
      <sw-breadcrumb slot="breadcrumbs">
        <sw-breadcrumb-item :title="$t('general.home')" to="dashboard" />
        <sw-breadcrumb-item :title="$tc('posts.post', 2)" to="#" active />
      </sw-breadcrumb>

      <template slot="actions">
        <sw-button
          v-show="totalPosts"
          variant="primary-outline"
          size="lg"
          @click="toggleFilter"
        >
          {{ $t('general.filter') }}
          <component :is="filterIcon" class="w-4 h-4 ml-2 -mr-1" />
        </sw-button>

        <sw-button
          tag-name="router-link"
          to="posts/create"
          variant="primary"
          size="lg"
          class="ml-4"
        >
          <plus-icon class="w-6 h-6 mr-1 -ml-2" />
          {{ $t('posts.add_post') }}
        </sw-button>
      </template>
    </sw-page-header>

    <slide-y-up-transition>
      <sw-filter-wrapper v-show="showFilters">
        <sw-input-group :label="$tc('posts.title')" class="flex-1 mt-2 ml-0">
          <sw-input
            v-model="filters.title"
            type="text"
            name="title"
            class="mt-2"
            autocomplete="off"
          />
        </sw-input-group>

        <sw-input-group
          :label="$tc('posts.thumbnail')"
          class="flex-1 mt-2 ml-0 lg:ml-6"
        >
          <sw-input
            v-model="filters.thumbnail"
            type="text"
            name="thumbnail"
            class="mt-2"
            autocomplete="off"
          />
        </sw-input-group>
        <sw-input-group
          :label="$tc('posts.content')"
          class="flex-1 mt-2 ml-0 lg:ml-6"
        >
          <sw-input
            v-model="filters.content"
            type="content"
            name=""
            class="mt-2"
            autocomplete="off"
          />
        </sw-input-group>

        <label
          class="absolute text-sm leading-snug text-gray-900 cursor-pointer"
          style="top: 10px; right: 15px"
          @click="clearFilter"
        >
          {{ $t('general.clear_all') }}</label
        >
      </sw-filter-wrapper>
    </slide-y-up-transition>

    <sw-empty-table-placeholder
      v-show="showEmptyScreen"
      :title="$t('posts.no_posts')"
      :description="$t('posts.list_of_posts')"
    >
      <satellite-icon class="mt-5 mb-4" />

      <sw-button
        slot="actions"
        tag-name="router-link"
        to="/admin/posts/create"
        size="lg"
        variant="primary-outline"
      >
        <plus-icon class="w-6 h-6 mr-1 -ml-2" />
        {{ $t('posts.add_new_post') }}
      </sw-button>
    </sw-empty-table-placeholder>


    <div v-show="!showEmptyScreen" class="relative table-container">
      <div
        class="relative flex items-center justify-between h-10 mt-5 list-none border-b-2 border-gray-200 border-solid"
      >
        <p class="text-sm">
          {{ $t('general.showing') }}: <b>{{ posts.length }}</b>
          {{ $t('general.of') }} <b>{{ totalPosts }}</b>
        </p>

        <sw-transition>
          <sw-dropdown v-if="selectedPosts.length">
            <span
              slot="activator"
              class="flex block text-sm font-medium cursor-pointer select-none text-primary-400"
            >
              {{ $t('general.actions') }}
              <chevron-down-icon class="h-5" />
            </span>

            <sw-dropdown-item @click="removeMultiplePosts">
              <trash-icon class="h-5 mr-3 text-gray-600" />
              {{ $t('general.delete') }}
            </sw-dropdown-item>
          </sw-dropdown>
        </sw-transition>
      </div>


      <div class="absolute z-10 items-center pl-4 mt-2 select-none md:mt-12">
        <sw-checkbox
          v-model="selectAllFieldStatus"
          variant="primary"
          size="sm"
          class="hidden md:inline"
          @change="selectAllPosts"
        />

        <sw-checkbox
          v-model="selectAllFieldStatus"
          :label="$t('general.select_all')"
          variant="primary"
          size="sm"
          class="md:hidden"
          @change="selectAllPosts"
        />
      </div>

      <sw-table-component
        ref="table"
        :data="fetchData"
        :show-filter="false"
        table-class="table"
      >
        <sw-table-column
          :sortable="false"
          :filterable="false"
          cell-class="no-click"
        >
          <div slot-scope="row" class="custom-control custom-checkbox">
            <sw-checkbox
              :id="row.id"
              v-model="selectField"
              :value="row.id"
              variant="primary"
              size="sm"
            />
          </div>
        </sw-table-column>

        <!-- [start] [name item] -->
        <sw-table-column 
        :sortable="true" 
        :label="$t('posts.title')" 
        show="title">
          <template slot-scope="row">
            <span>{{ $t('posts.title') }}</span>
            <router-link
              :to="{ path: `posts/${row.id}/edit` }"
              class="font-medium text-primary-500"
            >
              {{ row.title }}
            </router-link>
          </template>
        </sw-table-column>
        <!-- [end] [name item] -->

        <!-- [start] [price item] -->
        <sw-table-column
          :sortable="true"
          :label="$t('posts.thumbnail')"
          show="thumbnail"
        >
          <template slot-scope="row">
            <span> {{ $t('post.thumbnail') }} </span>
            <span>{{ row.thumbnail }}</span>
          </template>
        </sw-table-column>
        <!-- [end] [price item] -->

        <!-- [start] [show discount] [27-06] -->
        
        <sw-table-column
          :sortable="true"
          :label="$t('posts.content')"
          show="content"
        >
          <template slot-scope="row">
            <span> {{ $t('posts.content') }} </span>
            <span>{{ row.content }}</span>
          </template>
        </sw-table-column>
        <!-- [end] [show discount] [27-06] -->

        <!-- [start][show description][24-06] -->
        <sw-table-column
          :sortable="true"
          :label="$t('posts.view')"
          show="view"
        >
          <template slot-scope="row">
            <span> {{ $t('posts.view') }} </span>
            <span>{{ row.view }}</span>
          </template>
        </sw-table-column>
        <!-- [end][show description][24-06] -->

        <!-- [start] [added on item] -->
        <sw-table-column
          :sortable="true"
          :label="$t('posts.added_on')"
          sort-as="created_at"
          show="formattedCreatedAt"
        />
        <!-- [end] [added on item] -->

        <sw-table-column
          :sortable="true"
          :filterable="false"
          cell-class="action-dropdown"
        >
          <template slot-scope="row">
            <span> {{ $t('posts.action') }} </span>

            <sw-dropdown>
              <dot-icon slot="activator" />

              <sw-dropdown-item
                :to="`items/${row.id}/edit`"
                tag-name="router-link"
              >
                <pencil-icon class="h-5 mr-3 text-gray-600" />
                {{ $t('general.edit') }}
              </sw-dropdown-item>

              <sw-dropdown-item @click="removePost(row.id)">
                <trash-icon class="h-5 mr-3 text-gray-600" />
                {{ $t('general.delete') }}
              </sw-dropdown-item>
            </sw-dropdown>
          </template>
        </sw-table-column>
      </sw-table-component>
    </div>
  </base-page>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import {
  FilterIcon,
  XIcon,
  ChevronDownIcon,
  PencilIcon,
  TrashIcon,
  PlusIcon,
} from '@vue-hero-icons/solid'
import SatelliteIcon from '../../components/icon/SatelliteIcon'

export default {
  components: {
    SatelliteIcon,
    FilterIcon,
    XIcon,
    PlusIcon,
    ChevronDownIcon,
    PencilIcon,
    TrashIcon,
  },

  data() {
    return {
      id: null,
      showFilters: false,
      sortedBy: 'created_at',
      isRequestOngoing: true,

      filters: {
        title: '',
        thumbnail: '',
        content: '',
        view: ''
      },
    }
  },

  computed: {
    ...mapGetters('post', [
      'posts',
      'selectAllField',
      'selectedPosts',
      'totalPosts',
    ]),

    ...mapGetters('company', ['defaultCurrency']),

    showEmptyScreen() {
      return !this.totalPosts && !this.isRequestOngoing
    },

    filterIcon() {
      return this.showFilters ? 'x-icon' : 'filter-icon'
    },

    selectField: {
      get: function () {
        return this.selectedPosts
      },
      set: function (val) {
        console.log(val)
        this.selectPost(val)
      },
    },

    selectAllFieldStatus: {
      get: function () {
        return this.selectAllField
      },
      set: function (val) {
        this.setSelectAllState(val)
      },
    },
  },

  watch: {
    filters: {
      handler: 'setFilters',
      deep: true,
    },
  },

  mounted() {
    // this.fetchItemUnits({ limit: 'all' })
  },

  destroyed() {
    if (this.selectAllField) {
      this.selectAllPosts()
    }
  },

  methods: {
    ...mapActions('post', [
      'fetchPosts',
      'selectAllPosts',
      'selectPost',
      'setSelectAllState',
      'deletePost',
      'deleteMultiplePosts'
    ]),

    ...mapActions('notification', ['showNotification']),

    refreshTable() {
      this.$refs.table.refresh()
    },

    async fetchData({ page, filter, sort }) {
      let data = {
        limit: '4',
        search: this.filters.title !== null ? this.filters.title : '',
        orderByField: sort.fieldName || 'created_at',
        orderBy: sort.order || 'desc',
        page,
      }
      this.isRequestOngoing = true
      let response = await this.fetchPosts(data)
      this.isRequestOngoing = false
      return {
        data: response.data.posts.data,
        pagination: {
          totalPages: response.data.posts.last_page,
          currentPage: page,
        },
      }
    },

    setFilters() {
      this.refreshTable()
    },

    clearFilter() {
      this.filters = {
        name: '',
        unit: '',
        price: '',
        // [start] [show discount] [27-06]
        discount: '',
        // [end] [show discount] [27-06]
        description: '',
      }
    },

    toggleFilter() {
      if (this.showFilters) {
        this.clearFilter()
      }
      this.showFilters = !this.showFilters
    },

    async removePost(id) {
      this.id = id
      this.$swal({
        title: this.$t('general.are_you_sure'),
        text: this.$tc('items.confirm_delete'),
        icon: 'error',
        iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>`,
        showCancelButton: true,
        showConfirmButton: true,
      }).then(async (result) => {
        if (result.value) {
          let res = await this.deletePost({ ids: [id] })

          if (res.data.success) {
            this.showNotification({
              type: 'success',
              message: this.$tc('items.deleted_message'),
            })
            this.$refs.table.refresh()
            return true
          }

          if (res.data.error === 'item_attached') {
            this.showNotification({
              type: 'error',
              message:
                (this.$tc('items.item_attached_message'),
                this.$t('general.action_failed')),
            })
            return true
          }
          this.showNotification({
            type: 'error',
            message: res.data.message,
          })
          return true
        }
      })
    },

    async removeMultiplePosts() {
      this.$swal({
        title: this.$t('general.are_you_sure'),
        text: this.$tc('items.confirm_delete', 2),
        icon: 'error',
        iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>`,
        showCancelButton: true,
        showConfirmButton: true,
      }).then(async (result) => {
        if (result.value) {
          let res = await this.deleteMultiplePosts()

          if (res.data.success || res.data.posts) {
            this.showNotification({
              type: 'success',
              message: this.$tc('items.deleted_message', 2),
            })
            this.$refs.table.refresh()
          } else if (res.data.error) {
            this.showNotification({
              type: 'error',
              message: res.data.message,
            })
          }
        }
      })
    },
  },

  created() {
    // console.log(this.posts)
  }
}
</script>
