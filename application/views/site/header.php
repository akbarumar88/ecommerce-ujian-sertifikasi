<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/bootstrap-select.min.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('img/movie-icon-png.jpg') ?>" type="image/x-icon">
    <title>MOOVEE</title>
    <!-- Font Awesome Script -->
    <script src="https://kit.fontawesome.com/bc14fa0285.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('js/jquery-3.6.0.min.js') ?>"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="position: relative;">
        <a class="navbar-brand" href="<?= base_url() ?>">MOOVEE</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Genre</a>
                    <div class="dropdown-menu">
                        <div class="" style="display:flex;flex-wrap:wrap;width:600px">
                            <?php foreach ($genres as $genre) : ?>
                                <a style="width:25%" class="dropdown-item" href="<?= base_url('site/search_genre/' . $genre['id']) ?>"><?= $genre['genre'] ?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('site/indodax') ?>" >Data Indodax</a>
                </li>
            </ul>

            <form class="form-inline my-2 my-lg-0" action="<?= base_url('site/search')  ?>" style="position: relative;" autocomplete="off">
                <input class="form-control mr-sm-2" type="text" id="searchbar" name="q" placeholder="Search" value="<?= $this->input->get('q') ?>" required>
                <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>

                <!-- Form Popup Pencarian Ajax -->
                <div id="popup-search" style="position: absolute;top:40px;left:0;right:0;z-index: 1;" class="bg-white shadow"></div>
            </form>

            <ul class="navbar-nav ml-auto">
                <?php if (!$this->session->has_userdata('id')) : ?>
                    <li class="nav-item">
                        <a href="<?= base_url('auth/login') ?>" class="nav-link">Login</a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('auth/register') ?>" class="nav-link">Register</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link "><?= $this->session->nama_lengkap ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('auth/logout') ?>" class="nav-link">Logout</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('site/history') ?>" class="nav-link">Histori</a>
                    </li>

                    <!-- Menu khusus untuk Admin Web MOOVEE. -->
                    <?php if ($this->session->role == 1) : ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/index') ?>" class="nav-link">Admin Page</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

        </div>

    </nav>

    <script>
        $(document).ready(function() {
            $('#searchbar').on('input', function(e) {
                // console.log('onChange gan', e.target.value)
                let q = e.target.value

                if (q == '') {
                    // Kolom pencarian kosong,
                    $("#popup-search").empty() // Kosongkan container
                    return
                }
                $.ajax({
                    url: `<?= base_url() ?>/site/searchajax/${q}`,
                    dataType: 'JSON',
                    success: function(res) {
                        // console.log('res ajax', typeof res)

                        $("#popup-search").empty() // Kosongkan container
                        for (let i = 0; i < res.length; i++) {
                            const film = res[i];
                            // Append elemen hasil pencarian
                            // console.log('film gan', film)
                            $("#popup-search").append(`
                            <a href="<?= base_url() ?>/site/movie/${film.id}">
                                <div class="p-1" style="display: flex;flex-direction: row;">
                                    <div class="thumbnail-wrap mr-2" style="flex: 0.2;">
                                        <!-- Image Overlay -->
                                        <div class="overlay">
                                            <i style="font-size: 48px;color:white" class="fas fa-play-circle"></i>
                                        </div>
                                        <img width="100%" style="height:72px !important;padding: 0" src="<?= base_url() ?>/img/thumbnails/${film.id}.jpg" class="img-thumbnail rounded" alt="...">
                                    </div>
                                    <div class="" style="flex: 0.8;">
                                        <h6>${film.judul}</h6>
                                        <p style="font-size: 12px;color: #aaa;"><i class="fas fa-star"></i> ${film.rating}, Dec 23, 2021, <i class="fas fa-clock"></i> ${film.durasi} menit</p>
                                    </div>
                                </div>
                            </a>
                            `)
                        }
                    }
                });
            })
        })
    </script>
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Opening Tag Container -->
    <div class="container pt-5 pb-5">