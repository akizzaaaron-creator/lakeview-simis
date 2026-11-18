/* Lakeview SIMIS — small front-end helpers */
document.addEventListener('DOMContentLoaded', function () {
  // Auto-dismiss success alerts after 4 seconds.
  document.querySelectorAll('.alert-success').forEach(function (a) {
    setTimeout(function () {
      var al = bootstrap.Alert.getOrCreateInstance(a);
      al.close();
    }, 4000);
  });

  // Confirm before destructive actions on forms marked with data-confirm.
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!confirm(f.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    });
  });

  // Confirm for links marked with data-confirm (e.g. delete buttons).
  document.querySelectorAll('a[data-confirm]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      if (!confirm(a.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    });
  });
});
