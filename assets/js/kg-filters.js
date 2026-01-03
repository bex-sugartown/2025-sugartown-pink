/**
 * Knowledge Graph Filter Dropdown Toggle
 */
(function() {
  'use strict';
  
  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
  function init() {
    console.log('KG Filters: Initializing'); // Debug log
    
    const filterButtons = document.querySelectorAll('[data-filter-type]');
    
    if (filterButtons.length === 0) {
      console.warn('KG Filters: No filter buttons found');
      return;
    }
    
    console.log('KG Filters: Found', filterButtons.length, 'buttons');
    
    // Add click handlers to buttons
    filterButtons.forEach(button => {
      button.addEventListener('click', handleButtonClick);
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', handleOutsideClick);
  }
  
  function handleButtonClick(e) {
    e.preventDefault();
    console.log('KG Filters: Button clicked'); // Debug log
    
    const button = e.currentTarget;
    const dropdownId = button.getAttribute('aria-controls');
    const dropdown = document.getElementById(dropdownId);
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    
    // Close all dropdowns
    closeAllDropdowns();
    
    // Toggle this dropdown if it wasn't already open
    if (!isExpanded && dropdown) {
      dropdown.hidden = false;
      button.setAttribute('aria-expanded', 'true');
      console.log('KG Filters: Opened', dropdownId);
    }
  }
  
  function handleOutsideClick(e) {
    if (!e.target.closest('[data-filter-type]') &&
        !e.target.closest('.st-filter__dropdown')) {
      closeAllDropdowns();
    }
  }

  function closeAllDropdowns() {
    document.querySelectorAll('.st-filter__dropdown').forEach(d => {
      d.hidden = true;
    });

    document.querySelectorAll('[data-filter-type]').forEach(b => {
      b.setAttribute('aria-expanded', 'false');
    });
  }
})();