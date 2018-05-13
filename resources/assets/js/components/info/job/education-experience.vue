<template>
    <div>
        <education-form v-model="educationData" @complete="complete" @abandon="abandon" remind></education-form>
    </div>
</template>

<script type="text/ecmascript-6">
  import educationForm from '../base/education-form'
  import {ERR_OK} from 'Api/config'
  import {getCurrentUserFirstEducationExperience} from 'Api/education-experience'

  export default {
    data() {
      return {
        educationData: {}
      }
    },
    created() {
      // 请求当前登录用户的教育经历
      getCurrentUserFirstEducationExperience().then(response => {
        if (response.code === ERR_OK) {
          this.educationData = response.data
        }
      })
    },
    methods: {
      complete(newEducationData) {
        this.educationData = newEducationData
        this.$router.push({'name': 'job-advantage'})
      },
      abandon() {
        this.$router.back()
      }
    },
    components: {
      educationForm
    }
  }
</script>