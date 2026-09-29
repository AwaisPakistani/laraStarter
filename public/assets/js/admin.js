$(document).ready(function() {
    // Role Status Change active/inactive
  $(document).on('click', '#status_change', function() {
    var roleId = $(this).data('id');
    $.ajax({
      url: '/roles/' + roleId + '/change-status',
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        roleId: roleId
      },
      success: function(response) {
        if (response.success) {
          Toastify({
            text: response.message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#4fbe87",
          }).showToast();
          location.reload();
        } else {
          Toastify({
            text: response.message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#ff6b6b",
          }).showToast();
        }
      },
      error: function(xhr, status, error) {
        Toastify({
          text: "An error occurred while changing the status.",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "#ff6b6b",
        }).showToast();
      }
    });
  });
});
