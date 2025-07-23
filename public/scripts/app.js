new DataTable('#example');

 $(document).ready(function() {

        // Configuration de l'en-tête CSRF pour toutes les requêtes AJAX Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Ouvrir la modale pour ajouter un film
        $("#addNewFilmBtn").click(function() {
            $("#filmModalLabel").text("Ajouter un film");
            $("#filmId").val('');
            $("#filmForm")[0].reset();
            clearValidationErrors();
            $("#filmModal").modal('show');
        });

        // Ouvrir la modale pour modifier un film
        $(document).on('click', '.editFilmBtn', function() {
            var id = $(this).data('id');
            clearValidationErrors();

            // Charger les données via AJAX GET
            $.get('/films/' + id, function(data) {
                $("#filmModalLabel").text("Modifier le film");
                $("#filmId").val(data.id);
                $("#titre").val(data.titre);
                $("#description").val(data.description);
                $("#photo").val('');
                $("#filmModal").modal('show');
            });
        });

        // Soumission du formulaire (création ou update)
        $("#filmForm").submit(function(e) {
            e.preventDefault(); // Annuler le comportement par défaut

            clearValidationErrors();

            var formData = new FormData(this);
            var id = $("#filmId").val();
            var url = id ? '/films/update/' + id : '/films/store';
            var method = id ? 'POST' : 'POST'; // on utilise POST les deux cas

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false, // Important pour envoyer FormData
                contentType: false,  // Important pour envoyer FormData
                
                success: function(response) {
                     Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: response.success,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // recharge la page après la notification
                });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Erreurs de validation Laravel (422 Unprocessable Entity)
                        var errors = xhr.responseJSON.errors;
                        // Pour chaque erreur, montre le message à côté du champ correspondant
                        if (errors.titre) {
                            $("#titre").addClass("is-invalid");
                            $("#errorTitre").text(errors.titre[0]);
                        }
                        if (errors.description) {
                            $("#description").addClass("is-invalid");
                            $("#errorDescription").text(errors.description[0]);
                        }
                        if (errors.photo) {
                            $("#photo").addClass("is-invalid");
                            $("#errorPhoto").text(errors.photo[0]);
                        }
                    } else {
                         // SweetAlert erreur
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue, veuillez réessayer.',
                    });
                    }
                }
            });
        });

        // Supprimer un film
         $(document).on('click', '.deleteFilmBtn', function() {
        var id = $(this).data('id');

        // Confirmation avec SweetAlert
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous ne pourrez pas revenir en arrière !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/films/delete/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Supprimé',
                            text: response.success,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $("#filmRow" + id).remove();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Erreur lors de la suppression.',
                        });
                    }
                });
            }
        });
       });
        // Fonction pour retirer les classes d'erreur avant validation
        function clearValidationErrors() {
            $("#filmForm input, #filmForm textarea").removeClass("is-invalid");
            $("#errorTitre, #errorDescription, #errorPhoto").text('');
        }
    });


    (() => {
      'use strict'
      const form = document.getElementById('filmForm');

      // Écouteur sur la soumission du formulaire
      form.addEventListener('submit', event => {
        // La méthode checkValidity() vérifie si tous les champs requis sont valides
        if (!form.checkValidity()) {
          // Si un champ requis est invalide, on empêche la soumission du formulaire
          event.preventDefault()
          event.stopPropagation()
        }
        // Ajout de la classe Bootstrap 'was-validated' pour afficher les messages d'erreur  +237698394295 formateur
        form.classList.add('was-validated')
      }, false)
    })()