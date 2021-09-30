<template>
  <base-page>
    <!-- Page Header -->
    <sw-page-header :title="pageTitle" class="mb-3">
      <sw-breadcrumb slot="breadcrumbs">
        <sw-breadcrumb-item :title="$t('general.home')" to="/admin/dashboard" />
        <sw-breadcrumb-item :title="$tc('posts.post', 2)" to="/admin/posts" />
        <sw-breadcrumb-item
          v-if="$route.name === 'posts.edit'"
          :title="$t('posts.edit_post')"
          to="#"
          active
        />
        <sw-breadcrumb-item
          v-else
          :title="$t('posts.new_post')"
          to="#"
          active
        />
      </sw-breadcrumb>
    </sw-page-header>
    <!-- End Page Header -->

    <div class="grid grid-cols-12">
      <div class="col-span-12 md:col-span-6">
        <form action="" @submit.prevent="submitPost">

          <sw-card>
            <!-- input title -->
            <sw-input-group
              :label="$t('posts.title')"
              :error="titleError"
              class="mb-4"
              required
            >
              <sw-input
                v-model.trim="formData.title"
                :invalid="$v.formData.title.$error"
                class="mt-2"
                focus
                type="text"
                name="title"
                @input="$v.formData.title.$touch()"
              />
            </sw-input-group>
            <!-- end input title -->

            <!-- input thumbnail item -->
            <sw-input-group
              :label="$t('posts.thumbnail')"
              :error="thumbnailError"
              class="mb-4"
              required
            >
              <sw-input
                v-model.trim="formData.thumbnail"
                :invalid="$v.formData.thumbnail.$error"
                class="mt-2"
                focus
                type="file"
                name="thumbnail"
                @input="$v.formData.thumbnail.$touch()"
              />
            </sw-input-group>
            <!-- end input thumbnail item -->         
            
            <sw-input-group
              :label="$t('posts.content')"
              :error="contentError"
              class="mb-4"
            >
              <sw-textarea
                v-model="formData.content"
                rows="2"
                name="content"
                @input="$v.formData.content.$touch()"
              />
            </sw-input-group>

            <div class="mb-4">
              <sw-button
                :loading="isLoading"
                variant="primary"
                size="lg"
                class="flex justify-center w-full md:w-auto"
              >
                <save-icon v-if="!isLoading" class="mr-2 -ml-1" />
                {{ isEdit ? $t('posts.update_post') : $t('posts.save_post') }}
              </sw-button>
            </div>
          </sw-card>
        </form>
      </div>
    </div>
  </base-page>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { ShoppingCartIcon } from '@vue-hero-icons/solid'
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
    ShoppingCartIcon,
  },

  data() {
    return {
      isLoading: false,
      title: 'Add Item',
      units: [],
      taxes: [],
      taxPerItem: '',

      formData: {
        title: '',
        thumbnail: '',
        content: '',
      },

      money: {
        decimal: '.',
        thousands: ',',
        prefix: '$ ',
        precision: 2,
        masked: false,
      },
    }
  },

  computed: {
    ...mapGetters('company', ['defaultCurrencyForInput']),

    ...mapGetters('item', ['itemUnits']),

    ...mapGetters('taxType', ['taxTypes']),


    pageTitle() {
      if (this.$route.name === 'posts.edit') {
        return this.$t('posts.edit_post')
      }
      return this.$t('posts.new_post')
    },

    ...mapGetters('taxType', ['taxTypes']),

    isEdit() {
      if (this.$route.name === 'posts.edit') {
        return true
      }
      return false
    },

    isTaxPerItem() {
      return this.taxPerItem === 'YES' ? 1 : 0
    },

    getTaxTypes() {
      return this.taxTypes.map((tax) => {
        return {
          ...tax,
          tax_type_id: tax.id,
          tax_name: tax.name + ' (' + tax.percent + '%)',
        }
      })
    },

    titleError() {
      if (!this.$v.formData.title.$error) {
        return ''
      }

      if (!this.$v.formData.title.required) {
        return this.$t('validation.required')
      }

    },

    thumbnailError() {
      if (!this.$v.formData.thumbnail.$error) {
        return ''
      }

      if (!this.$v.formData.thumbnail.required) {
        return this.$t('validation.required')
      }
    },

    contentError() {
      if (!this.$v.formData.content.$error) {
        return ''
      }

      if (!this.$v.formData.content.maxLength) {
        return this.$t('validation.description_maxlength')
      }
    },
  },

  created() {
    this.loadData()
  },

  mounted() {
    this.setTaxPerItem()

    this.$v.formData.$reset()
  },

  validations: {
    formData: {
      title: {
        required,
      },

      thumbnail: {
        required,
      },

      content: {
        required
      },
    },
  },

  methods: {
    ...mapActions('post', [
      'addPost',
      // 'fetchItem',
      // 'updateItem',
    ]),

    ...mapActions('taxType', ['fetchTaxTypes']),

    ...mapActions('company', ['fetchCompanySettings']),

    ...mapActions('modal', ['openModal']),

    ...mapActions('notification', ['showNotification']),

    async setTaxPerItem() {
      let response = await this.fetchCompanySettings(['tax_per_item'])

      if (response.data) {
        response.data.tax_per_item === 'YES'
          ? (this.taxPerItem = 'YES')
          : (this.taxPerItem = 'NO')
      }
    },

    async loadData() {
      if (this.isEdit) {
        let response = await this.fetchItem(this.$route.params.id)

        this.formData = { ...response.data.item, unit: null }

        this.fractional_price = response.data.item.price

       
      } else {
        this.fetchTaxTypes({ limit: 'all' })
      }
    },

    async submitPost() {
      this.$v.formData.$touch()

      if (this.$v.$invalid) {
        return false
      }


      if (this.formData.unit) {
        this.formData.unit_id = this.formData.unit.id
      }
      console.log(this.formData)
      let response
      this.isLoading = true

      if (this.isEdit) {
        response = await this.updateItem(this.formData)
      } else {
        let data = {
          ...this.formData,
        }
        response = await this.addPost(data)
      }


      if (response.data) {
        this.isLoading = false

        if (!this.isEdit) {
          this.showNotification({
            type: 'success',
            message: this.$tc('posts.created_message'),
          })
          this.$router.push('/admin/posts')
          return true
        } else {
          this.showNotification({
            type: 'success',
            message: this.$tc('items.updated_message'),
          })
          this.$router.push('/admin/items')
          return true
        }
        this.showNotification({
          type: 'error',
          message: response.data.error,
        })
      }
    },

    async addItemUnit() {
      this.openModal({
        title: this.$t('settings.customization.items.add_item_unit'),
        componentName: 'ItemUnit',
      })
    },
  },
}
</script>
