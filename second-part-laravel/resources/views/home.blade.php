<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Prueba Técnica - SatoriTech</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>

<body>
    <header>
        <h1>Rick And Morty API</h1>
    </header>
    <main>
        <section>
            <label for="location">Search for residents by location ID</label>
            <div class="input-group">
                <input type="text" class="form-control" id="location" placeholder="Escribe un numero" aria-describedby="basic-addon1" />
                <button class="btn btn-dark" type="button" id="searchButton">Buscar</button>
            </div>
        </section>
    </main>

    <div class="container mb-5">
        <div class="row" id="residentsContainer"></div>
    </div>

    <div class="container mt-5 mb-5">
        <div class="savedResidents">
            <h2>Saved residents</h2>
            <form action="/show-characters" method="get">
                @csrf
                <button type="submit" class="btn btn-dark">Show saved residents</button>
            </form>
        </div>
        @if(isset($characters) && count($characters) > 0)
        <div class="row">
            @foreach ($characters as $character)
            <div class="col mt-5">
                <div class="card pointer m-auto" style="width: 235px;">
                    <img src="{{ $character->image }}" class="card-img" alt="{{ $character->image }}" data-bs-toggle="modal" data-bs-target="#characterModal" data-character-index="${index}" />
                    <h6 class="card-title m-auto py-2">{{ $character->name }}</h6>
                    <div class="ps-3">
                        <p><strong class="pe-2">Status:</strong>{{ $character->status }} </p>
                        <p><strong class="pe-2">Specie:</strong> {{ $character->species }} </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="savedResidentsEmpty">
            <p>No saved characters were found.</p>
        </div>
        @endif
    </div>


    <div class="modal fade" id="characterModal" tabindex="-1" aria-labelledby="characterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="characterModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-dark">
                    <div class="d-flex">
                        <img src="" id="characterImage" alt="Personaje" class="img-fluid mb-3" />
                        <div class="details">
                            <p><strong>Name:</strong> <span id="characterName"></span></p>
                            <p><strong>Status:</strong> <span id="characterStatus"></span></p>
                            <p><strong>Specie:</strong> <span id="characterSpecies"></span></p>
                            <p><strong>Origin:</strong> <span id="characterOrigin"></span></p>
                        </div>
                    </div>
                    <p><strong>Episodes:</strong></p>
                    <div id="characterEpisodes"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
</body>

</html>