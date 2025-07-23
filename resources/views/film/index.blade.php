<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

     <meta name="csrf-token" content="{{ csrf_token() }}">

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">

    <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script defer src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>

    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Import jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Import Bootstrap JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 CSS et JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"  />


    <script defer src="{{ asset('scripts/app.js') }}"></script>
    <title>{{!empty($meta_title) ? $meta_title : '' }} - CINE</title>
</head>
<body>

      

    <div class="container mt-5">
        <button class="btn btn-primary mb-3" id="addNewFilmBtn"><i class="fas fa-plus me-1"></i>Ajouter un film</button>
        <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Date creation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="filmsTableBody">
            @foreach ($films as $film)
            <tr id="filmRow{{$film->id}}">
                <td>{{ $film->id }}</td>
                <td>
                    @if($film->photo)
                        <img src="{{ asset('upload/films/' . $film->photo) }}" width="60" height="60" style="object-fit: cover; border-radius: 15px;">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $film->titre }}</td>
                <td>{{ $film->description }}</td>
                <td >{{ date('d-m-y H:i A', strtotime($film->created_at)) }}</td>
                <td>
                    <button class="btn btn-success btn-sm btn-details" data-id="{{ $film->id }}"><i class="fa-regular fa-eye"></i></i></button>
                    <button class="btn btn-info btn-sm editFilmBtn" data-id="{{ $film->id }}"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm deleteFilmBtn" data-id="{{ $film->id }}"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
        </table>
    </div>

    <!-- Modal pour ajouter / modifier -->
<div class="modal fade" id="filmModal" tabindex="-1" aria-labelledby="filmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="filmForm" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filmModalLabel">Ajouter un film</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="filmId" name="filmId" value="">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                        <div class="invalid-feedback" id="errorTitre">t</div>
                         <div class="invalid-feedback">Veuillez saisir le statut.</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        <div class="invalid-feedback" id="errorDescription"></div>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        <div class="invalid-feedback" id="errorPhoto"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" id="saveFilmBtn">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="filmDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filmTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body d-flex">
        <img id="filmImage" src="" alt="Image du film" style="max-width: 200px; margin-right: 15px; object-fit: cover; border-radius: 10px;">
        <div id="filmDescription" style="flex-grow:1;"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>