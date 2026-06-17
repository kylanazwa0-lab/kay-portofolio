document.addEventListener('DOMContentLoaded', () => {
    // Fetch data from admin JSON
    const timestamp = new Date().getTime();
    fetch('admin/data/portfolio.json?v=' + timestamp)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            renderSkills(data.skills);
            renderProjects(data.projects);
            renderDiagrams(data.diagrams);
            // Re-initialize filter buttons if they exist
            if (typeof initFilters === 'function') initFilters();
        })
        .catch(error => {
            console.error('Error fetching portfolio data:', error);
            // Fallback to data folder if admin/data not found
            fetch('data/portfolio.json?v=' + timestamp)
            .then(res => res.json())
            .then(data => {
                renderSkills(data.skills);
                renderProjects(data.projects);
                renderDiagrams(data.diagrams);
            }).catch(e => console.error('Fallback error:', e));
        });
});

function renderSkills(skills) {
    const container = document.getElementById('skills-container');
    if (!container || !skills) return;

    let html = '';
    skills.forEach(cat => {
        let itemsHtml = '';
        if(cat.items) {
            cat.items.forEach(item => {
                let levelClass = 'level-' + (item.level ? item.level.toLowerCase() : 'beginner');
                if (cat.category === 'AI-Assisted Dev' && ['Claude AI', 'Gemini', 'ChatGPT'].includes(item.name)) {
                    levelClass = 'ai-tag';
                }
                itemsHtml += `<span class="skill-tag ${levelClass}">${item.name}</span>`;
            });
        }
        html += `
        <div class="skill-category">
          <div class="skill-cat-title"><span class="cat-icon"></span> ${cat.category}</div>
          <div class="skill-tags">${itemsHtml}</div>
        </div>
        `;
    });
    container.innerHTML = html;
}

function renderProjects(projects) {
    const container = document.getElementById('projects-container');
    if (!container || !projects) return;

    let html = '';
    projects.forEach((proj, idx) => {
        const isFeatured = idx < 2 ? 'featured' : '';
        let badgeClass = 'badge-live';
        if (proj.status === 'indev') badgeClass = 'badge-indev';

        let highlightsHtml = '';
        if (proj.highlights) {
            proj.highlights.forEach(hl => {
                highlightsHtml += `<div class="ph-item"><span class="ph-num">${hl.num}</span><span class="ph-label">${hl.label}</span></div>`;
            });
        }

        let featuresHtml = '';
        if (proj.features) {
            proj.features.forEach(ft => {
                featuresHtml += `<li>${ft}</li>`;
            });
        }

        let techHtml = '';
        if (proj.tech) {
            proj.tech.forEach(tc => {
                techHtml += `<span class="tech-chip">${tc}</span>`;
            });
        }

        let link = '';
        if (proj.link_url && proj.link_url.trim() !== '') {
            link = `<a href="${proj.link_url}" target="_blank" class="btn-primary btn-demo-ui" style="display: block; text-decoration: none;">Buka Website Proyek</a>`;
        } else if (proj.attachment && proj.attachment.trim() !== '') {
            let ext = proj.attachment.split('.').pop().toLowerCase();
            let btnText = 'Download / Lihat Berkas';
            if(ext === 'zip') btnText = 'Download Source Code (.zip)';
            if(ext === 'pdf') btnText = 'Lihat Dokumen (.pdf)';
            if(ext === 'xls' || ext === 'xlsx') btnText = 'Download Analisis (.xlsx)';
            
            link = `<a href="${proj.attachment}" target="_blank" class="btn-primary btn-demo-ui" style="display: block; text-decoration: none; background: linear-gradient(135deg, #0d9488, #2563eb);">${btnText}</a>`;
        } else {
            link = '<button class="btn-primary btn-demo-ui" onclick="openDemoModal(this)">Lihat Detail</button>';
        }

        let numberStr = proj.number || String(idx + 1).padStart(2, '0');

        html += `
        <div class="project-card ${isFeatured}" data-tags="${proj.tags || ''}" id="proj-${proj.id}">
          <div class="project-header">
            <div class="project-meta">
              <span class="project-number">${numberStr}</span>
              <div class="project-badges">
                <span class="badge ${badgeClass}">${proj.status_label || ''}</span>
                <span class="badge badge-type">${proj.type || ''}</span>
              </div>
            </div>
          </div>
          <div class="project-body">
            <div class="project-icon-wrap"><span class="project-icon"></span></div>
            <h3 class="project-title">${proj.title || ''}</h3>
            <p class="project-tagline">${proj.tagline || ''}</p>
            <p class="project-desc">${proj.description || ''}</p>
            ${highlightsHtml ? `<div class="project-highlights-row">${highlightsHtml}</div>` : ''}
            <div class="project-accordion">
              <details class="acc-item">
                <summary class="acc-header">Fitur &amp; Sistem</summary>
                <div class="acc-body">
                  <ul>${featuresHtml}</ul>
                </div>
              </details>
            </div>
            <div class="project-tech-stack">
              ${techHtml}
            </div>
            <div class="project-actions" style="margin-top: 1.5rem; text-align: center;">
              ${link}
            </div>
          </div>
        </div>
        `;
    });
    container.innerHTML = html;
}

function renderDiagrams(diagrams) {
    const container = document.getElementById('diagrams-container');
    if (!container || !diagrams || diagrams.length === 0) return;

    let tabsHtml = '<div class="diagram-tabs">';
    diagrams.forEach((diag, idx) => {
        tabsHtml += `<button class="dtab-btn ${idx === 0 ? 'active' : ''}" data-dtab="${diag.id}">${diag.label}</button>`;
    });
    tabsHtml += '</div>';

    let contentHtml = '';
    diagrams.forEach((diag, idx) => {
        let highlightsHtml = '';
        if (diag.highlights) {
            diag.highlights.forEach(hl => {
                highlightsHtml += `<li>${hl}</li>`;
            });
        }

        let imgHtml = '';
        if (diag.image) {
            imgHtml = `<img src="${diag.image}" alt="Diagram" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">`;
        } else {
            imgHtml = `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--bg-card);border-radius:12px;color:var(--text-gray);border:1px solid var(--border);">No Image Available</div>`;
        }

        contentHtml += `
        <div class="diagram-content ${idx === 0 ? 'active' : ''}" id="diag-${diag.id}">
            <div class="diagram-card">
              <div class="diagram-placeholder-img">
                ${imgHtml}
              </div>
              <div class="diagram-info">
                <h3>${diag.title || ''}</h3>
                <p>${diag.description || ''}</p>
                <ul class="diagram-highlights">
                  ${highlightsHtml}
                </ul>
                <span class="btn-diagram"> Lihat di Dokumentasi</span>
              </div>
            </div>
          </div>
        `;
    });

    container.innerHTML = tabsHtml + contentHtml;

    const dtabs = container.querySelectorAll('.dtab-btn');
    const dcontents = container.querySelectorAll('.diagram-content');
    dtabs.forEach(btn => {
      btn.addEventListener('click', () => {
        dtabs.forEach(t => t.classList.remove('active'));
        dcontents.forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        const targetId = btn.getAttribute('data-dtab');
        const targetContent = container.querySelector('#diag-' + targetId);
        if(targetContent) {
          targetContent.classList.add('active');
        }
      });
    });
}

function initFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    if(!filterBtns.length) return;

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.getAttribute('data-filter');
        projectCards.forEach(card => {
          if (filter === 'all') {
            card.style.display = 'block';
          } else {
            if (card.getAttribute('data-tags') && card.getAttribute('data-tags').includes(filter)) {
              card.style.display = 'block';
            } else {
              card.style.display = 'none';
            }
          }
        });
      });
    });
}
