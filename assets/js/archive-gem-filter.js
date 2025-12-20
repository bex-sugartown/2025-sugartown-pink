/**
 * Gem Archive Filtering (Client-Side)
 * File: archive-gem-filter.js
 * Enqueue in functions.php
 */

(function() {
    'use strict';
    
    // Wait for DOM to load
    document.addEventListener('DOMContentLoaded', function() {
        
        // Check if we're on the gem archive page
        if (!document.querySelector('.gem-archive')) return;
        
        init();
    });
    
    function init() {
        // Get URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const projectFilter = urlParams.get('project');
        const statusFilter = urlParams.get('status');
        const categoryFilter = urlParams.get('category');
        
        // Apply filters if present in URL
        if (projectFilter || statusFilter || categoryFilter) {
            filterGems({
                project: projectFilter,
                status: statusFilter,
                category: categoryFilter
            });
        }
        
        // Set up click handlers for filter links
        setupFilterLinks();
        
        // Optional: Add filter UI (uncomment if you want dropdowns)
        // addFilterUI();
    }
    
    /**
     * Filter gems based on criteria
     */
    function filterGems(filters) {
        const cards = document.querySelectorAll('.gem-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            let shouldShow = true;
            
            // Check project filter
            if (filters.project) {
                const cardProject = card.dataset.project;
                if (cardProject !== filters.project) {
                    shouldShow = false;
                }
            }
            
            // Check status filter
            if (filters.status) {
                const cardStatus = card.dataset.status;
                if (cardStatus !== filters.status) {
                    shouldShow = false;
                }
            }
            
            // Check category filter
            if (filters.category) {
                const cardCategory = card.dataset.category;
                if (cardCategory !== filters.category) {
                    shouldShow = false;
                }
            }
            
            // Apply visibility
            if (shouldShow) {
                card.style.display = 'block';
                card.removeAttribute('data-filtered');
                visibleCount++;
            } else {
                card.style.display = 'none';
                card.setAttribute('data-filtered', 'hidden');
            }
        });
        
        // Update count display (if exists)
        updateCount(visibleCount, cards.length);
        
        // Show "no results" message if needed
        showNoResults(visibleCount === 0);
    }
    
    /**
     * Set up click handlers for filter links
     */
    function setupFilterLinks() {
        // Project links
        document.querySelectorAll('.project-link').forEach(link => {
            link.addEventListener('click', function(e) {
                // If it's already a link with ?project= param, let it work normally
                if (this.href.includes('?project=')) {
                    return; // Let the default link behavior work
                }
            });
        });
        
        // Category links
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.href.includes('?category=')) {
                    return;
                }
            });
        });
    }
    
    /**
     * Update visible count display
     */
    function updateCount(visible, total) {
        let countEl = document.querySelector('.filter-count');
        
        if (!countEl) {
            // Create count element if it doesn't exist
            const header = document.querySelector('.archive-header');
            if (header) {
                countEl = document.createElement('p');
                countEl.className = 'filter-count';
                header.appendChild(countEl);
            }
        }
        
        if (countEl) {
            const urlParams = new URLSearchParams(window.location.search);
            const isFiltered = urlParams.has('project') || urlParams.has('status') || urlParams.has('category');
            
            if (isFiltered) {
                countEl.textContent = `Showing ${visible} of ${total} gems`;
                countEl.style.color = '#FF69B4';
                countEl.style.fontWeight = '600';
            } else {
                countEl.textContent = `${total} gems`;
                countEl.style.color = '';
                countEl.style.fontWeight = '';
            }
        }
    }
    
    /**
     * Show/hide "no results" message
     */
    function showNoResults(show) {
        let noResults = document.querySelector('.no-results-message');
        
        if (show && !noResults) {
            const grid = document.querySelector('.gem-grid');
            noResults = document.createElement('div');
            noResults.className = 'no-results-message';
            noResults.innerHTML = `
                <p style="text-align: center; padding: 40px; color: #999; font-size: 1.1rem;">
                    No gems match these filters.
                    <a href="?" style="color: #FF69B4; text-decoration: underline; margin-left: 8px;">Clear filters</a>
                </p>
            `;
            grid.parentNode.insertBefore(noResults, grid.nextSibling);
        } else if (!show && noResults) {
            noResults.remove();
        }
    }
    
    /**
     * Optional: Add dropdown filter UI
     * Call this from init() if you want filter dropdowns
     */
    function addFilterUI() {
        const archive = document.querySelector('.gem-archive');
        const header = document.querySelector('.archive-header');
        
        if (!archive || !header) return;
        
        // Collect unique values
        const projects = new Set();
        const statuses = new Set();
        const categories = new Set();
        
        document.querySelectorAll('.gem-card').forEach(card => {
            if (card.dataset.project) projects.add(card.dataset.project);
            if (card.dataset.status) statuses.add(card.dataset.status);
            if (card.dataset.category) categories.add(card.dataset.category);
        });
        
        // Create filter UI
        const filterDiv = document.createElement('div');
        filterDiv.className = 'archive-filters';
        filterDiv.innerHTML = `
            <div class="filter-group">
                <label>Project:</label>
                <select id="filter-project">
                    <option value="">All Projects</option>
                    ${Array.from(projects).sort().map(p => `<option value="${p}">${p}</option>`).join('')}
                </select>
            </div>
            
            <div class="filter-group">
                <label>Status:</label>
                <select id="filter-status">
                    <option value="">All Statuses</option>
                    ${Array.from(statuses).sort().map(s => `<option value="${s}">${s}</option>`).join('')}
                </select>
            </div>
            
            <div class="filter-group">
                <label>Category:</label>
                <select id="filter-category">
                    <option value="">All Categories</option>
                    ${Array.from(categories).sort().map(c => `<option value="${c}">${c}</option>`).join('')}
                </select>
            </div>
            
            <button class="filter-reset" onclick="window.location='?'">Clear Filters</button>
        `;
        
        header.after(filterDiv);
        
        // Set up change handlers
        ['project', 'status', 'category'].forEach(filterType => {
            const select = document.getElementById(`filter-${filterType}`);
            select.addEventListener('change', function() {
                const params = new URLSearchParams(window.location.search);
                
                if (this.value) {
                    params.set(filterType, this.value);
                } else {
                    params.delete(filterType);
                }
                
                // Update URL without page reload
                const newUrl = params.toString() ? `?${params.toString()}` : window.location.pathname;
                window.history.pushState({}, '', newUrl);
                
                // Apply filters
                filterGems({
                    project: document.getElementById('filter-project').value,
                    status: document.getElementById('filter-status').value,
                    category: document.getElementById('filter-category').value
                });
            });
            
            // Set initial value from URL
            const urlParams = new URLSearchParams(window.location.search);
            const value = urlParams.get(filterType);
            if (value) {
                select.value = value;
            }
        });
    }
    
})();
