<?php namespace Telenok\Core\Model\File;

class FileCategory extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'file_category';
	protected $ruleList = ['title' => ['required', 'min:1']];
 
    public function categoryFile()
    {
        return $this->belongsToMany('\App\Telenok\Core\Model\File\File', 'pivot_relation_m2m_category_file', 'category', 'category_file')->withTimestamps();
    } 
}
