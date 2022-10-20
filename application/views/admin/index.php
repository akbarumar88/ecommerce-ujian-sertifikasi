<?php flash('succadd') ?>

<h3 class="mb-4">Daftar Film MOOVEE</h3>

<a class="btn btn-primary" href="<?= base_url('admin/addmovie') ?>" role="button"><i class="fas fa-plus"></i> Tambah Film</a>

<form class="form-inline mt-3 " action="">
    <input class="form-control mr-sm-2" type="text" name="q" placeholder="Search" value="<?= $this->input->get('q') ?>">
    <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>
</form>

<div class="mb-4"></div>
<div class="row">
    <?php foreach ($movies as $movie) :  ?>
        <div class="col-lg-1 col-md-1 col-sm-2 col-2 pr-0 mb-4">

            <a href="<?= base_url('admin/editmovie/' . $movie['id']) ?>">
                <div class="thumbnail-wrap">
                    <!-- Durasi -->
                    <div class="durasi py-1 px-2" style="font-size: 10px;">
                        <i class="fas fa-clock"></i>
                        <b><?= $movie['durasi'] ?>m</b>
                    </div>
                    <!-- Rating -->
                    <div class="rating py-1 px-2" style="font-size: 10px;">
                        <i class="fas fa-star"></i>
                        <b><?= $movie['rating'] ?></b>
                    </div>
                    <!-- HD Badge -->
                    <div class="hd py-1 px-2" style="font-size: 10px;"><b>HD</b></div>
                    <!-- Image Overlay -->
                    <div class="overlay">
                        <i style="font-size: 48px;color:white" class="fas fa-play-circle"></i>
                    </div>
                    <img width="100%" style="height:150px !important;padding: 0" src="<?= base_url('img/thumbnails/' . $movie['id'] . '.jpg') ?>" class="img-thumbnail rounded" alt="...">
                </div>
                <p class="text-center mt-2"><?= limit($movie['judul'], 4) ?></p>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<?php 

$currentPage = !empty($this->input->get('p')) ? $this->input->get('p') : 1;

?>


<nav aria-label="...">
    <ul class="pagination">
        <li class="page-item <?= $currentPage == 1 ? 'disabled' : ''  ?>">
            <a class="page-link">Previous</a>
        </li>
        <?php for ($i=1; $i<=$totalPage; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'disabled' : ''  ?>"><a class="page-link" href="<?= "?p=$i" ?>"><?= $i ?></a></li>
        <?php endfor; ?>
        <li class="page-item <?= $currentPage == $totalPage ? 'disabled' : ''  ?>">
            <a class="page-link" href="#">Next</a>
        </li>
    </ul>
</nav>