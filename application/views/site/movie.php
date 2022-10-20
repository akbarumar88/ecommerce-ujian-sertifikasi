<!-- <h3 class="mb-4">Hasil Pencarian untuk</h3> -->
<?php

$tglRilis = formatDate($movie['tglrilis'], "M d, Y");

?>

<?php if (!empty($movie['kualitas'])) : ?>
    <div class="row">
        <div class="col">
            <iframe class="rounded" id="videoplayer" src="<?= $movie['kualitas'][0]['url']  ?>" width="100%" height="640" allow="autoplay" allowfullscreen frameborder="0"></iframe>
        </div>
    </div>

    <!-- Kualitas Film -->
    <div class="dropdown mt-2 mb-2">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownKualitas" data-toggle="dropdown" aria-expanded="false">
            <?= $movie['kualitas'][0]['kualitas'] ?>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownKualitas">
            <?php foreach ($movie['kualitas'] as $qu) : ?>
                <button class="dropdown-item" data-value="<?= $qu['url'] ?>" type="button"><?= $qu['kualitas'] ?></button>
            <?php endforeach; ?>
        </div>
    </div>
<?php else : ?>
    <p class="text-center"><i class="fas fa-tools display-1"></i></p>
    <h3 class="mb-4 text-center">Oops, film belum tersedia.</h3>
    <p class="text-center">Mohon maaf, film ini masih sedang dalam proses. Tim MOOVEE akan menyelesaikannya sesegera mungkin.</p>
<?php endif; ?>

<h2 class="mt-4"><?= $movie['judul'] ?></h2>
<p>Released on <?= $tglRilis ?></p>
<p class=""><?= $movie['sinopsis'] ?></p>

<h2 class="mt-4 mb-2">Informasi Film</h2>

<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-8 mb-lg-0 mb-4">
        <img width="100%" style="height:350px !important;padding: 0" src="<?= base_url('img/thumbnails/' . $movie['id'] . '.jpg') ?>" class="img-thumbnail rounded" alt="...">
    </div>
    <div class="col-lg-9 col-md-12 col-sm-12 col-12">
        <h4><?= $movie['judul'] ?></h4>
        <div class="row">
            <div class="col-6">
                <p class="mb-2"><i class="fas fa-dot-circle text-danger" style="font-size:12px;"></i> <b>STATUS :</b> Completed</p>
                <p><i class="fas fa-dot-circle text-danger" style="font-size:12px;"></i> <b>RILIS :</b> <?= $tglRilis ?></p>
            </div>

            <div class="col-6">
                <p class="mb-2"><i class="fas fa-dot-circle text-danger" style="font-size:12px;"></i> <b>DURASI :</b> <?= $movie['durasi'] ?> menit</p>
                <p><i class="fas fa-dot-circle text-danger" style="font-size:12px;"></i> <b>RATING :</b> <?= $movie['rating'] ?> <i class="fas fa-star text-warning"></i></p>
            </div>
        </div>
        <?php foreach ($movie['genre'] as $genre) : ?>
            <a href="<?= base_url('site/search_genre/'.$genre['idgenre']) ?>" class="badge badge-warning badge-pill py-2 px-3 mr-1 mb-2" style="font-size:14px"><?= $genre['genre'] ?></a>
        <?php endforeach; ?>
    </div>
</div>

<?php if (!empty($related_movies)) : ?>
    <h2 class="mt-5 mb-2">Mungkin Anda Suka</h2>
    <div class="row row-cols-lg-5 row-cols-md-4 row-cols-sm-3 row-cols-2">
        <?php foreach ($related_movies as $movie) :  ?>
            <div class="col pr-0 mb-4">

                <a href="<?= base_url('site/movie/' . $movie['id']) ?>">
                    <div class="thumbnail-wrap">
                        <!-- Durasi -->
                        <div class="durasi py-1 px-2">
                            <i class="fas fa-clock"></i>
                            <b><?= $movie['durasi'] ?>m</b>
                        </div>
                        <!-- Rating -->
                        <div class="rating py-1 px-2">
                            <i class="fas fa-star"></i>
                            <b><?= $movie['rating'] ?></b>
                        </div>
                        <!-- HD Badge -->
                        <div class="hd py-1 px-2"><b>HD</b></div>
                        <!-- Image Overlay -->
                        <div class="overlay">
                            <i style="font-size: 48px;color:white" class="fas fa-play-circle"></i>
                        </div>
                        <img width="100%" style="height:300px !important;padding: 0" src="<?= base_url('img/thumbnails/' . $movie['id'] . '.jpg') ?>" class="img-thumbnail rounded" alt="...">
                    </div>
                    <h6 class="text-center mt-2"><?= limit($movie['judul'], 7) ?></h6>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>