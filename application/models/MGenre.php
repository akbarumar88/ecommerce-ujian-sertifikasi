<?php 

class MGenre extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
    }

    public function find($id)
    {
        $res = $this
            ->db
            ->get_where('genre', [
                'id' => $id
            ])
            ->row_array();
        return $res;
    }
}
