<template>
  <base-page>
    <!-- Page Header -->
    <div class="text-3xl font-bold">
      {{ post.title }}
    </div>
    
    <div class="flex my-3 justify-between">
      <div class="flex">
        <span
        v-for="type in post.types" :key="type.id"
        class="bg-gray-800 mr-3 text-white rounded-lg p-1 block w-36 text-center"
        >
          {{ type.name }}
        </span>
      </div>
      <div class="flex items-center">
        <div class="mr-4">
          Comment: {{ commentPost.length }}
        </div>
        <router-link 
        :to="{ name: 'posts.edit', params: { id: post.id } }"
        class="bg-green-700 p-2 text-center text-white rounded-sm shadow-lg"
        >Edit
        </router-link>
      </div>
    </div>
    <!-- End Page Header -->

    <div>
      <div v-html="post.content"></div>
      <div class="comment mt-5 text-right">
        <sw-input-group
        class="mb-4"
        label="Comment">
        <sw-editor
          v-model="post.comment"
          :set-editor="post.comment"
          :placeholder="placeholder"
          variant="header-editor"
          input-class="border-none"
          class="text-area-field"
          name="content"
        />
        </sw-input-group>
        <sw-button
        variant="primary"
        size="lg"
        class=""
        @click="submitComment"
        >
          <save-icon class="mr-2 -ml-1" />
          Add Comment
        </sw-button>
      </div>

      <sw-table-component
        ref="tableComment"
        :data="commentPost"
        :show-filter="false"
        table-class="table"
      >

        <sw-table-column 
        :sortable="true" 
        :label="$tc('comments.title')" 
        show="title">
          <template slot-scope="row">
            <h1 class="font-bold">{{ row.name_user_comment }}</h1>
            <p class="py-4"  v-html="row.content"></p>
          </template>
        </sw-table-column>

        <sw-table-column
          :sortable="true"
          :filterable="false"
          cell-class="action-dropdown"
        >
          <template slot-scope="row">
            <span> {{ $t('posts.action') }} </span>

            <sw-dropdown>
              <dot-icon slot="activator" />
              <sw-dropdown-item @click="removeComment(row.id)">
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
import TheSiteHeaderVue from '../layouts/partials/TheSiteHeader.vue'
const {
  required,
  minLength,
  numeric,
  minValue,
  maxLength,
} = require('vuelidate/lib/validators')

export default {
  components: {
    FilterIcon,
    XIcon,
    PlusIcon,
    ChevronDownIcon,
    PencilIcon,
    TrashIcon,
  },

  data() {
    return {
      title: 'Post',

      post: {
        title: '',
        content: '',
        thumbnail: '',
        types: [],
        comments: []
      },
      commentPost: [],

      placeholder: 'Please input content'
    }
  },

  computed: {
    ...mapGetters('comment', ['comments', 'totalComments']),
    ...mapGetters('post', ['posts']),
  },

  created() {
    this.loadData()
  },

  methods: {
    ...mapActions('post', ['fetchPost']),

    ...mapActions('comment', [
      'fetchComments',
      'addComment',
      'deleteComment'
    ]),

    ...mapActions('modal', ['openModal']),

    ...mapActions('notification', ['showNotification']),

    async refreshComment(id) {
        await this.fetchComments({ limit: 'all', orderByField: 'id' })
        this.commentPost = this.comments.filter(function(comment) {
            return comment.post_id === id
        })
    },
    
    async loadData() {
      let response = await this.fetchPost(this.$route.params.id)
      let res = await this.fetchComments({ limit: 'all', orderByField: 'id' })
      this.post = {
        ...response.data.post
      }
      this.refreshComment(this.post.id)
    },

    async submitComment() {
      let response 
      let data = {
        'content': this.post.comment,
        'user_id': 1,
        'post_id': this.$route.params.id
      }
      response = await this.addComment(data)

      if(response.data) {

        this.post.comment = ''
        
        this.refreshComment(this.post.id)

        this.showNotification({
          type: 'success',
          message: this.$tc('comments.updated_message'),
        })

        return true

      }
    },

    removeComment(id) {
      this.$swal({
        title: this.$t('general.are_you_sure'),
        text: this.$tc('comments.confirm_delete'),
        icon: 'error',
        iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600"fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>`,
        showCancelButton: true,
        showConfirmButton: true,
      }).then(async (result) => {
        if (result.value) {
          let res = await this.deleteComment({ ids: [id] })

          this.refreshComment(this.post.id)

          if (res.data.success) {

            this.showNotification({
              type: 'success',
              message: this.$tc('items.deleted_message'),
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
    }

  },
}
</script>
