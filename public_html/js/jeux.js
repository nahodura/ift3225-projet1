(async function() {

  const messageEl = document.getElementById('message');
  const container = document.getElementById('jeuxContainer');
  const addForm = document.getElementById('addForm');

  function showMessage(text, type) {
    if (!messageEl) return;
    messageEl.textContent = text;
    messageEl.className = 'message ' + (type || '');
  }


  function escapeHtml(text) {
    return text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  // charge la liste des jeux 
  async function loadJeux() {
    const res = await fetch('api/jeux/liste_json.php', { method: 'GET' });
    if (!res.ok) return;
    const jeux = await res.json();  // conforme aux exemples fetch+json fileciteturn4file12
    container.innerHTML = '';
    jeux.forEach(jeu => container.appendChild(renderJeu(jeu)));
  }

  function renderJeu(jeu) {
    const div = document.createElement('div');
    div.dataset.jeuId = jeu.jeu_id;

    // propriétés du jeu
    const pNom = document.createElement('p');
    pNom.textContent = 'Nom: ' + escapeHtml(jeu.nom);
    div.appendChild(pNom);
    const pGenre = document.createElement('p');
    pGenre.textContent = 'Genre: ' + escapeHtml(jeu.genre || '');
    div.appendChild(pGenre);
    const pPlateforme = document.createElement('p');
    pPlateforme.textContent = 'Plateforme: ' + escapeHtml(jeu.plateforme || '');
    div.appendChild(pPlateforme);
    const pDesc = document.createElement('p');
    pDesc.textContent = 'Description: ' + escapeHtml(jeu.description || '');
    div.appendChild(pDesc);

     if (jeu.image) {
      const img = document.createElement('img');
      img.src = 'img/' + encodeURIComponent(jeu.image);
      img.alt = '';
      img.width = 100;
      img.height = 100;
      img.style.objectFit = 'cover';
      div.appendChild(img);
    }

    //  formulaire de suppression
    const delForm = document.createElement('form');
    delForm.innerHTML = '<input type="hidden" name="jeu_id" value="' + jeu.jeu_id + '"><button type="submit">Supprimer</button>';
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
        div.remove();
      }
    });
    div.appendChild(delForm);

    //  formulaire modif.
    const editBtn = document.createElement('button');
    editBtn.type = 'button';
    editBtn.textContent = 'Modifier';
    editBtn.addEventListener('click', () => showEditForm(div, jeu));
    div.appendChild(editBtn);

    return div;
  }

  // formulaire modif. inline
  function showEditForm(containerEl, jeu) {
    containerEl.innerHTML = '';
    const form = document.createElement('form');
    form.innerHTML = 
      '<input type="hidden" name="jeu_id" value="' + jeu.jeu_id + '">' +
      '<input type="hidden" name="current_image" value="' + escapeHtml(jeu.image || '') + '">' +
      '<label>Nom</label><input name="nom" value="' + escapeHtml(jeu.nom) + '"><br>' +
      '<label>Genre</label><input name="genre" value="' + escapeHtml(jeu.genre || '') + '"><br>' +
      '<label>Plateforme</label><input name="plateforme" value="' + escapeHtml(jeu.plateforme || '') + '"><br>' +
      '<label>Description</label><textarea name="description">' + escapeHtml(jeu.description || '') + '</textarea><br>' +
      '<button type="submit">Enregistrer</button> ' +
      '<button type="button" class="cancel">Annuler</button>';

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
