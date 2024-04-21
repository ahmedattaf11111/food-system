<template>
  <!-- Modal -->
  <div
    class="modal fade"
    id="deleteConfirmationModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            {{ $t("DELETE_CONFIRMATION_HEADER") }}
          </h5>
          <button
            @click="$emit('closed')"
            type="button"
            data-dismiss="modal"
            aria-label="Close"
          >
            <i class="fa fa-close text-secondary"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="delete-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-x-octagon"
            >
              <polygon
                points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"
              ></polygon>
              <line x1="15" y1="9" x2="9" y2="15"></line>
              <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
          </div>

          <div class="first-message">
            {{ $t("Are you sure") }}
          </div>
          <div class="second-message">
            {{ $t("All data related to this may get deleted.") }}
          </div>
          <div>
            <button
              data-dismiss="modal"
              @click="$emit('confirm')"
              type="button"
              class="btn process"
            >
              {{ $t("PROCESS") }}
            </button>
            <button
              @click="$emit('closed')"
              type="button"
              data-dismiss="modal"
              class="btn border cancel"
            >
              {{ $t("CANCEL") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  emits: ["confirm"],
};
</script>

<style lang="scss">
#deleteConfirmationModal {
  .delete-icon {
    color: #e03a1d;
  }
  .process,.cancel{
    font-size: 13px !important;
  }
  .process {
    width: 85px;
    height: 43px;
    background: #e03a1d;
  }
  .cancel {
    width: 85px;
    height: 43px;
    color: #202342;
    background: #f4f6f8;
    margin: 0 5px;
  }
  .first-message {
    margin-top: 6px;
    font-size: 15px !important;
  }
  .second-message {
    color: #637381 !important;
    font-size: 13px !important;
    margin-bottom: 27px;
  }
  .modal-header {
    display: flex;
    align-items: center;
    .modal-title {
      font-size: 16px !important;
    }
    button {
      background: none;
      border: none;
      i {
        font-size: 17px !important;
      }
    }
  }

  button {
    color: white;
  }
  .question-icon {
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
  }
  .modal-body,
  .modal-footer {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .modal-body {
    padding: 2rem !important;
  }
  .modal-footer {
    button {
      width: 60px;
    }
  }
  .modal-body {
    flex-direction: column;
  }
  body[dir="rtl"] & {
    .first-message,
    .modal-title {
      font-weight: 500;
    }
  }
  body[dir="ltr"] & {
    .first-message,
    .modal-title {
      font-weight: 600;
    }
  }
}
</style>