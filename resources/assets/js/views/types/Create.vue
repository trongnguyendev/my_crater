<template>
  <base-page>
    <!-- Page Header -->
    <sw-page-header :title="pageTitle" class="mb-3">
      <sw-breadcrumb slot="breadcrumbs">
        <sw-breadcrumb-item :title="$t('general.home')" to="/admin/dashboard" />
        <sw-breadcrumb-item :title="$tc('types.type', 2)" to="/admin/types" />
        <sw-breadcrumb-item
          v-if="$route.name === 'types.edit'"
          :title="$t('types.edit_type')"
          to="#"
          active
        />
        <sw-breadcrumb-item
          v-else
          :title="$t('types.new_type')"
          to="#"
          active
        />
      </sw-breadcrumb>
    </sw-page-header>
    <!-- End Page Header -->

    <div class="grid grid-cols-12">
      <div class="col-span-12 md:col-span-6">
        <form action="" @submit.prevent="submitType">

          <sw-card>
            <sw-input-group
              :label="$t('types.name')"
              :error="nameError"
              class="mb-4"
              required
            >
              <sw-input
                v-model.trim="formData.name"
                :invalid="$v.formData.name.$error"
                class="mt-2"
                focus
                type="text"
                name="name"
                @input="$v.formData.name.$touch()"
              />
            </sw-input-group>

            <sw-input-group
              :label="$t('types.description')"
              :error="descriptionError"
              class="mb-4"
            >
              <sw-textarea
                v-model="formData.description"
                rows="2"
                name="description"
                @input="$v.formData.description.$touch()"
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
                {{ isEdit ? $t('types.update_type') : $t('types.save_type') }}
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
      title: 'Add Type',

      formData: {
        name: '',
        description: '',
      },
    }
  },

  computed: {
    ...mapGetters('company', ['defaultCurrencyForInput']),

    ...mapGetters('item', ['itemUnits']),

    ...mapGetters('taxType', ['taxTypes']),

   

    pageTitle() {
      if (this.$route.name === 'types.edit') {
        return this.$t('types.edit_type')
      }
      return this.$t('types.new_type')
    },

    ...mapGetters('taxType', ['taxTypes']),

    isEdit() {
      if (this.$route.name === 'types.edit') {
        return true
      }
      return false
    },

    nameError() {
      if (!this.$v.formData.name.$error) {
        return ''
      }

      if (!this.$v.formData.name.required) {
        return this.$t('validation.required')
      }

      if (!this.$v.formData.name.minLength) {
        return this.$tc(
          'validation.name_min_length',
          this.$v.formData.name.$params.minLength.min,
          { count: this.$v.formData.name.$params.minLength.min }
        )
      }
    },

    descriptionError() {
      if (!this.$v.formData.description.$error) {
        return ''
      }

      if (!this.$v.formData.description.maxLength) {
        return this.$t('validation.description_maxlength')
      }
    },
  },

  created() {
    this.loadData()
  },

  mounted() {
    this.$v.formData.$reset()
  },

  validations: {
    formData: {
      name: {
        required,
        minLength: minLength(3),
      },

      description: {
        maxLength: maxLength(65000),
      },
    },
  },

  methods: {
    ...mapActions('type', [
      'addType',
      'fetchType',
      'updateType',
    ]),

    ...mapActions('company', ['fetchCompanySettings']),

    ...mapActions('modal', ['openModal']),

    ...mapActions('notification', ['showNotification']),

    async loadData() {
      if (this.isEdit) {
        let response = await this.fetchType(this.$route.params.id)
        this.formData = { ...response.data.type}
      }
    },

    async submitType() {
      this.$v.formData.$touch()

      if (this.$v.$invalid) {
        return false
      }

      let response
      this.isLoading = true

      if (this.isEdit) {
        response = await this.updateType(this.formData)
        
      } else {
        let data = {
          ...this.formData,
        }
        response = await this.addType(data)
      }


      if (response.data) {
        this.isLoading = false

        if (!this.isEdit) {
          this.showNotification({
            type: 'success',
            message: this.$tc('items.created_message'),
          })
          this.$router.push('/admin/types')
          return true
        } else {
          this.showNotification({
            type: 'success',
            message: this.$tc('items.updated_message'),
          })
          this.$router.push('/admin/types')
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
