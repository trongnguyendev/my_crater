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

    <div class="">
      <form action="" @submit.prevent="submitPost">

          <sw-card>

            <div class="grid-cols-12 gap-8 mt-6 mb-8 lg:grid">
              <div class="col-span-8 pr-0 col-span-5 pr-0">
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

                <sw-input-group
                :label="$t('posts.content')"
                :error="contentError"
                  class="mb-4"
                >

                  <sw-editor
                    v-model="formData.content"
                    :set-editor="formData.content"
                    :placeholder="placeholder"
                    variant="header-editor"
                    input-class="border-none"
                    class="text-area-field"
                    name="content"
                    @input="$v.formData.content.$touch()"
                  />
                </sw-input-group>
              </div>
              <div class="grid-cols-1 col-span-4 gap-4 mt-8 lg:gap-6 lg:mt-0 lg:grid-cols-2">
                <sw-input-group
                class="mb-4"
                :label="$t('types.type')"
                >
                  <sw-checkbox 
                    v-for="type in getTypesPost"
                    :key="type.id"
                    :label="type.name"
                    :value="type.id"
                    :id="type.id"
                    name="typePost"
                    v-model="formData.types"
                  />
                </sw-input-group>

                <sw-input-group
                  :label="$t('posts.thumbnail')"
                  :error="thumbnailError"
                  class="mb-4"
                  required
                >
                  <img v-if="$route.name === 'posts.edit'" :src="'/storage/post/' + formData.thumbnail"  alt="">                

                  <input type="file" ref="file" name="file" id="" class="mt-4" @change="uploadFIle">
                </sw-input-group>

              </div>
            </div>
            
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
      title: 'Add Post',

      formData: {
        title: '',
        thumbnail: '',
        content: '',
        types: []
      },

      placeholder: 'Please input content'
    }
  },
  computed: {
    ...mapGetters('type', [
      'types'
    ]),
    pageTitle() {
      if (this.$route.name === 'posts.edit') {
        return this.$t('posts.edit_post')
      }
      return this.$t('posts.new_post')
    },

    getTypesPost() {
      return this.types
    },

    isEdit() {
      if (this.$route.name === 'posts.edit') {
        return true
      }
      return false
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
      'fetchPost',
      'updateThumbnail',
      'updatePost'
    ]),

    ...mapActions('type', [
      'fetchTypes'
    ]),

    ...mapActions('modal', ['openModal']),

    ...mapActions('notification', ['showNotification']),

    uploadFIle() {
      this.formData.thumbnail = this.$refs.file.files[0]
    },
    async loadData() {
      if (this.isEdit) {
        let response = await this.fetchPost(this.$route.params.id)
        
        let type_of_post = response.data.post.types

        this.formData = { ...response.data.post}
        this.formData.types = type_of_post.map(type => type.id)
      }
      await this.fetchTypes({ limit : 'all' })
      this.getTypes = this.types     
    },

    async submitPost() {
      
      let data_form = new FormData()

      data_form.append('thumbnail', this.formData.thumbnail)
      data_form.append('title', this.formData.title)
      data_form.append('content', this.formData.content)
      data_form.append('types', this.formData.types)

      if (this.$v.$invalid) {
        return false
      }
      let response
      let response_update 
      this.isLoading = true

      if (this.isEdit) {
        let data_post = {
          'title': this.formData.title,
          'content': this.formData.content,
          'types': this.formData.types,
          'id'  : this.$route.params.id
        }
        response = await this.updatePost(data_post)

        if(response.data && ((typeof this.formData.thumbnail) != 'string')) {
          let response_thumbnail = await this.updateThumbnail([data_form, this.$route.params.id])
        }
      } else {
        response = await this.addPost(data_form)
      }      

      if (response.data || response_update.data) {
        this.isLoading = false

        if (!this.isEdit) {
          this.showNotification({
            type: 'success',
            message: this.$tc('posts.created_message'),
          })
          this.$router.push('/admin/posts')
          return true
        } else {          
          if(response.data) {
            this.showNotification({
              type: 'success',
              message: this.$tc('posts.updated_message'),
            })
            this.$router.push('/admin/posts')
            return true
          }          
        }
        this.showNotification({
          type: 'error',
          message: response.data.error,
        })
      }
    },

  },
}
</script>
