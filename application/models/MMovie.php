<?php

class MMovie extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param
     */
    public function search($q = '', $limit=15, $offset=0)
    {
        $movies = $this
            ->db
            ->query("SELECT * FROM film WHERE judul LIKE '%$q%' ORDER BY judul ASC LIMIT $limit OFFSET $offset")
            ->result_array();
        // dd($movies);
        return $movies;
    }

    public function movies_count()
    {
        $movies = $this
            ->db
            ->query("SELECT COUNT(*) as count FROM film")
            ->row_array();
        // dd($movies);
        return $movies['count'];
    }

    public function searchCount($q = '')
    {
        $movies = $this
            ->db
            ->query("SELECT COUNT(*) as count FROM film WHERE judul LIKE '%$q%'")
            ->row_array();
        // dd($movies);
        return $movies['count'];
    }

    /**
     * @param
     */
    public function latest_aired($limit=15, $offset=0)
    {
        $movies = $this
            ->db
            ->query("SELECT * FROM film ORDER BY tglrilis DESC LIMIT $limit OFFSET $offset")
            ->result_array();
        // dd($movies);
        return $movies;
    }

    public function recently_added($limit=15, $offset=0)
    {
        $movies = $this
            ->db
            ->query("SELECT * FROM film ORDER BY id DESC LIMIT $limit OFFSET $offset")
            ->result_array();
        // dd($movies);
        return $movies;
    }

    public function find($id)
    {
        $movie = $this
            ->db
            ->query("SELECT * FROM film WHERE id = $id")
            ->row_array();
        $kualitas = $this
            ->db
            ->query("SELECT * FROM film_kualitas where idfilm = $id")
            ->result_array();
        $genre = $this
            ->db
            ->query("SELECT idgenre, g.genre from film_genre fg 
                inner join genre g on fg.idgenre = g.id 
                where idfilm = $id")
            ->result_array();
        $movie['kualitas'] = $kualitas;
        $movie['genre'] = $genre;
        // dd($movie);
        return $movie;
    }

    public function related($id)
    {
        // Mengambil data film terkait berdasarkan genrenya.
        $genre = $this
            ->db
            ->query("SELECT idgenre, g.genre from film_genre fg 
                inner join genre g on fg.idgenre = g.id 
                where idfilm = $id")
            ->result_array();
        $idgenres = array_map(function ($genre) {
            return $genre['idgenre'];
        }, $genre);
        // dd($idgenres);
        $movieByGenre = $this
            ->db
            ->distinct()
            ->select('f.*')
            ->from('film_genre fg')
            ->join('film f', 'fg.idfilm=f.id')
            ->where_in('idgenre', $idgenres) //Mencari dengan genre terkait
            ->where_not_in('f.id', [$id]) //Film saat ini tidak ikut ter-select
            ->limit(10)
            ->get()
            ->result_array();
        // dd($movieByGenre);
        return ($movieByGenre);
    }

    public function findByGenre($idgenres, $limit=15,$offset=0)
    {
        // Mengambil data film berdasarkan genrenya.
        $movieByGenre = $this
            ->db
            ->distinct()
            ->select('f.*')
            ->from('film_genre fg')
            ->join('film f', 'fg.idfilm=f.id')
            ->where_in('idgenre', $idgenres)
            ->order_by('judul', 'ASC')
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->result_array();
        // dd($movieByGenre);
        return ($movieByGenre);
    }

    public function findByGenreCount($idgenres)
    {
        // Mengambil data film berdasarkan genrenya.
        $movieByGenre = $this
            ->db
            ->distinct()
            ->select('f.*')
            ->from('film_genre fg')
            ->join('film f', 'fg.idfilm=f.id')
            ->where_in('idgenre', $idgenres)
            ->get()
            ->result_array();
        // dd($movieByGenre);
        return count($movieByGenre);
    }

    public function genres()
    {
        $genres = $this
            ->db
            ->get('genre')
            ->result_array();
        return $genres;
    }

    public function add($data)
    {
        // Insert data film
        $this
            ->db
            ->insert('film', [
                'judul' => $data['judul'],
                'sinopsis' => $data['sinopsis'],
                'tglrilis' => $data['tglrilis'],
                'durasi' => $data['durasi'],
                'rating' => $data['rating'],
            ]);
        $idfilm = $this->db->insert_id();
        
        // Insert data genre
        $data_genre = [];
        foreach ($data['genre'] as $i => $idgenre) {
            $data_genre[] = ['idfilm' => $idfilm, 'idgenre' => $idgenre];
        }
        $this
            ->db
            ->insert_batch("film_genre", $data_genre);
        // Insert data kualitas
        $this
            ->db
            ->insert_batch("film_kualitas", [
                ['idfilm' => $idfilm, 'kualitas' => '480p', 'url' => $data['link_sd']],
                ['idfilm' => $idfilm, 'kualitas' => '720p', 'url' => $data['link_hd']],
            ]);
        
        return $idfilm;
    }

    public function update($data)
    {
        // Insert data film
        $this
            ->db
            ->where('id', $data['id'])
            ->update('film', [
                'judul' => $data['judul'],
                'sinopsis' => $data['sinopsis'],
                'tglrilis' => $data['tglrilis'],
                'durasi' => $data['durasi'],
                'rating' => $data['rating'],
            ]);
        $idfilm = $data['id'];
        
        // Delete semua genre dari film saat ini
        $this->db->delete('film_genre', ['idfilm' => $idfilm]);
        // Insert data genre
        $data_genre = [];
        foreach ($data['genre'] as $i => $idgenre) {
            $data_genre[] = ['idfilm' => $idfilm, 'idgenre' => $idgenre];
        }
        $this
            ->db
            ->insert_batch("film_genre", $data_genre);
        
        // Hapus semua link film
        $this->db->delete('film_kualitas', ['idfilm' => $idfilm]);
        // Insert data kualitas
        $this
            ->db
            ->insert_batch("film_kualitas", [
                ['idfilm' => $idfilm, 'kualitas' => '480p', 'url' => $data['link_sd']],
                ['idfilm' => $idfilm, 'kualitas' => '720p', 'url' => $data['link_hd']],
            ]);

        
        return $idfilm;
    }

    public function delete($id)
    {
        // Hapus genrenya dulu
        $this->db->delete('film_genre', ['idfilm' => $id]);
        // Hapus kualitasnya
        $this->db->delete('film_kualitas', ['idfilm' => $id]);
        // Hapus data histori film
        $this->db->delete('user_histori', ['idfilm' => $id]);

        // Hapus filmnya
        $this->db->delete('film', ['id' => $id]);
    }
}
