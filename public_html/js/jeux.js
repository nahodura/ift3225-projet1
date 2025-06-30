(async function() {

const messageEl = document.getElementById('message');
const container = document.getElementById('jeuxContainer');
const template = document.getElementById('jeu-template');
const addForm = document.getElementById('addForm');

function escapeHtml(text) {
    return text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  // affiche les message
function showMessage(text, type) {
    if (!messageEl) return;
    messageEl.textContent = text;
    messageEl.className = 'message ' + (type || '');
  }

  // charge la liste des jeux 
async function loadJeux() {
    const query = window.location.search;
    const url = 'api/jeux/liste_json.php' + (query ? query : '');  // pour filtrer
    const res = await fetch(url, { method: 'GET' });
    if (!res.ok) return;
    const jeux = await res.json(); 
    container.innerHTML = '';
    jeux.forEach(jeu => container.appendChild(renderJeu(jeu)));
  }

    // affiche un jeu
function renderJeu(jeu) {
    const el = template.content.firstElementChild.cloneNode(true);
    el.dataset.jeuId = jeu.jeu_id;

    el.querySelector('.nom').textContent = jeu.nom;
    el.querySelector('.genre').textContent = jeu.genre ? 'Genre: ' + jeu.genre : '';
    el.querySelector('.plateforme').textContent = jeu.plateforme ? 'Plateforme: ' + jeu.plateforme : '';
    el.querySelector('.description').textContent = jeu.description ? 'Description: ' + jeu.description : '';

    const img = el.querySelector('.image');
    if (jeu.image) {
      img.src = 'img/' + encodeURIComponent(jeu.image);
      img.alt = jeu.nom;
      img.style.display = 'block';
    } else {
      img.remove();
    }

    const delForm = el.querySelector('.delete-form');
    delForm.elements.jeu_id.value = jeu.jeu_id;
    delForm.addEventListener('submit', async e => {
      e.preventDefault();
      if (!confirm('Confirmer la suppression ?')) return;
      const params = new URLSearchParams();
      params.append('jeu_id', jeu.jeu_id);
      const resp = await fetch('api/jeux/supprimer.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: params.toString()
      });
      const data = await resp.json();
      if (data.success) {
        showMessage('Jeu supprimé.', 'success');
        el.remove();
      }
    });

    el.querySelector('.edit').addEventListener('click', () => showEditForm(el, jeu));

    return el;
  }

  // formulaire modif. inline
  function showEditForm(containerEl, jeu) {
    containerEl.innerHTML = '';
    const form = document.createElement('form');
    form.className = 'form-style';
    form.innerHTML =
      '<input type="hidden" name="jeu_id" value="' + jeu.jeu_id + '">' +
      '<input type="hidden" name="current_image" value="' + escapeHtml(jeu.image || '') + '">' +
      '<div class="mb-2"><label class="form-label">Nom</label><input class="form-control" name="nom" value="' + escapeHtml(jeu.nom) + '"></div>' +
      '<div class="mb-2"><label class="form-label">Genre</label><input class="form-control" name="genre" value="' + escapeHtml(jeu.genre || '') + '"></div>' +
      '<div class="mb-2"><label class="form-label">Plateforme</label><input class="form-control" name="plateforme" value="' + escapeHtml(jeu.plateforme || '') + '"></div>' +
      '<div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description">' + escapeHtml(jeu.description || '') + '</textarea></div>' +
      '<button type="submit" class="btn btn-purple w-100 mb-2">Enregistrer</button>' +
      '<button type="button" class="btn btn-secondary w-100 cancel">Annuler</button>';

    form.querySelector('.cancel').addEventListener('click', e => {
      e.preventDefault();
      loadJeux();
    });

   // POST 
    form.addEventListener('submit', async e => {
      e.preventDefault();
      const p = new URLSearchParams();
      ['jeu_id','current_image','nom','genre','plateforme','description'].forEach(key => {
        p.append(key, form.elements[key].value);
      });
      const resp = await fetch('api/jeux/modifier.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: p.toString()
      });
      const data = await resp.json();
      if (data.success) {
        showMessage('Jeu modifié.', 'success');
        containerEl.replaceWith(renderJeu(data.jeu));
      }
    });

    containerEl.appendChild(form);
  }

  // ajout de jeu
  if (addForm) {
    addForm.addEventListener('submit', async e => {
      e.preventDefault();
      const p = new URLSearchParams();
      ['nom','genre','plateforme','description'].forEach(key => {
        p.append(key, addForm.elements[key].value);
      });
      
      p.append('ajax', '1');

      const resp = await fetch('api/jeux/ajouter.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: p.toString()
      });
      const data = await resp.json();
      if (data.success) {
        addForm.reset();
        showMessage('Jeu ajouté.', 'success');
        container.prepend(renderJeu(data.jeu));
      } else if (data.error) {
        showMessage('Erreur: ' + data.error, 'error');
      }
    });
  }

  loadJeux();
})();
