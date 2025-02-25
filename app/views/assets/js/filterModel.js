// filterModal.js
function openFilterModal() {
    document.getElementById('filterModalOverlay').classList.add('active');
    document.getElementById('filterModal').classList.add('active');
  }
  function closeFilterModal() {
    document.getElementById('filterModalOverlay').classList.remove('active');
    document.getElementById('filterModal').classList.remove('active');
  }
  function changeFilterCategory(el) {
    // Remove active class from all sidebar items
    document.querySelectorAll('.filter-categories li').forEach(item => item.classList.remove('active'));
    el.classList.add('active');
    var category = el.getAttribute('data-category');
    // Hide all panels in the right panel
    document.querySelectorAll('.filter-options-panel .filter-section').forEach(panel => panel.style.display = 'none');
    // Show the selected panel
    var selected = document.getElementById('filter-' + category);
    if (selected) { selected.style.display = 'block'; }
  }
  function removeAllFilters() {
    // Uncheck all checkboxes and clear search inputs
    document.querySelectorAll('.filter-options-panel input').forEach(input => {
      if (input.type === 'checkbox') input.checked = false;
      else if (input.type === 'text' || input.type === 'number') input.value = '';
    });
  }
  function applyFilters() {
    // Gather all filter selections (this can be extended to send via AJAX or build a GET form)
    alert('Filters applied (demo)');
    closeFilterModal();
  }
  