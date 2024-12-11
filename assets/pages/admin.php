<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - FitnessPro Gym</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <h1>Tableau de bord d'administration</h1>
        
        <div class="dashboard">
            <div class="stat-card">
                <h3>Nombre de réservations</h3>
                <p id="reservationCount">150</p>
            </div>
            <div class="stat-card">
                <h3>Nombre d'activités</h3>
                <p id="activityCount">10</p>
            </div>
            <div class="stat-card">
                <h3>Nombre total de membres</h3>
                <p id="memberCount">500</p>
            </div>
        </div>

        <h2>Gestion des activités</h2>
        <button id="toggleFormBtn" class="btn">Ajouter une activité</button>
        <button id="toggleSearchBtn" class="btn btn-secondary">Rechercher</button>

        <div id="addActivityForm" class="form-container">
            <h3>Ajouter une nouvelle activité</h3>
            <form id="activityForm">
                <div class="form-group">
                    <label for="activityName">Nom de l'activité:</label>
                    <input type="text" id="activityName" required>
                </div>
                <div class="form-group">
                    <label for="coachName">Nom du coach:</label>
                    <input type="text" id="coachName" required>
                </div>
                <button type="submit" class="btn">Ajouter</button>
            </form>
        </div>

        <div id="searchContainer" class="search-container">
            <input type="text" id="searchInput" placeholder="Rechercher une activité...">
            <button id="searchBtn" class="btn">Rechercher</button>
        </div>

        <table class="activities-table">
            <thead>
                <tr>
                    <th>Nom de l'activité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="activitiesTableBody">
                <!-- Les activités seront ajoutées ici dynamiquement -->
            </tbody>
        </table>
    </div>

    <script>
        // Données factices pour les activités
        const activities = [
            { id: 1, name: "Yoga", coach: "Marie Dupont", isValid: true },
            { id: 2, name: "Musculation", coach: "Pierre Martin", isValid: false },
            { id: 3, name: "Zumba", coach: "Sophie Lefebvre", isValid: true }
        ];

        // Fonction pour afficher les activités
        function displayActivities() {
            const tableBody = document.getElementById('activitiesTableBody');
            tableBody.innerHTML = '';
            activities.forEach(activity => {
                const row = `
                    <tr>
                        <td>${activity.name}</td>
                        <td>
                            <button class="btn ${activity.isValid ? 'btn-secondary' : ''}" onclick="toggleValidity(${activity.id})">${activity.isValid ? 'Invalider' : 'Valider'}</button>
                            <button class="btn btn-secondary" onclick="showEditForm(${activity.id})">Modifier</button>
                            <button class="btn btn-danger" onclick="deleteActivity(${activity.id})">Supprimer</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        }

        // Fonction pour basculer la validité d'une activité
        function toggleValidity(id) {
            const activity = activities.find(a => a.id === id);
            if (activity) {
                activity.isValid = !activity.isValid;
                displayActivities();
            }
        }

        // Fonction pour afficher le formulaire de modification
        function showEditForm(id) {
            const activity = activities.find(a => a.id === id);
            if (activity) {
                document.getElementById('activityName').value = activity.name;
                document.getElementById('coachName').value = activity.coach;
                document.getElementById('addActivityForm').style.display = 'block';
                document.getElementById('activityForm').onsubmit = (e) => {
                    e.preventDefault();
                    activity.name = document.getElementById('activityName').value;
                    activity.coach = document.getElementById('coachName').value;
                    displayActivities();
                    document.getElementById('addActivityForm').style.display = 'none';
                };
            }
        }

        // Fonction pour supprimer une activité
        function deleteActivity(id) {
            const index = activities.findIndex(a => a.id === id);
            if (index !== -1) {
                activities.splice(index, 1);
                displayActivities();
            }
        }

        // Gestionnaire d'événements pour le bouton d'ajout/masquage du formulaire
        document.getElementById('toggleFormBtn').addEventListener('click', () => {
            const form = document.getElementById('addActivityForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
                document.getElementById('toggleFormBtn').textContent = 'Masquer le formulaire';
            } else {
                form.style.display = 'none';
                document.getElementById('toggleFormBtn').textContent = 'Ajouter une activité';
            }
        });

        // Gestionnaire d'événements pour le bouton de recherche
        document.getElementById('toggleSearchBtn').addEventListener('click', () => {
            const searchContainer = document.getElementById('searchContainer');
            if (searchContainer.style.display === 'none') {
                searchContainer.style.display = 'block';
            } else {
                searchContainer.style.display = 'none';
            }
        });

        // Gestionnaire d'événements pour le formulaire d'ajout d'activité
        document.getElementById('activityForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('activityName').value;
            const coach = document.getElementById('coachName').value;
            activities.push({ id: activities.length + 1, name, coach, isValid: true });
            displayActivities();
            document.getElementById('activityForm').reset();
            document.getElementById('addActivityForm').style.display = 'none';
            document.getElementById('toggleFormBtn').textContent = 'Ajouter une activité';
        });

        // Initialisation de l'affichage
        displayActivities();
    </script>
</body>
</html>