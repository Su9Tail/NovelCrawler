<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use QL\QueryList;

class SiteController extends Controller
{
    private $url = 'http://www.qu.la';

    public function __construct()
    {
//        set_time_limit(60);
    }


    public function index()
    {
        if (!Storage::exists('novels/novels.json')) {
            dd('暂时未采集任何内容，请联系系统管理员');
        }
        $novels = json_decode(Storage::get('novels/novels.json'));
        foreach ($novels as $novel) {
            $array = explode('/', $novel->href);
            $novel->id = $array[4];
        }
        return view('novels.index', [
            'novels' => $novels
        ]);
    }

    public function catalog($id)
    {
        $novels = json_decode(Storage::get('novels/novels.json'));
        foreach ($novels as $novel) {
            if ($novel->href == 'http://www.qu.la/book/' . $id . '/') {
                $chapters = json_decode(Storage::get('novels/' . $novel->title . '/chapters.json'));
                foreach ($chapters as $chapter) {
                    $array = explode('/', $chapter->href);
                    $array = explode('.', $array[5]);
                    $chapter->id = $array[0];
                }
                $array = explode('/', $novel->href);
                $novel->id = $array[4];
                return view('novels.catalog', [
                    'novel' => $novel,
                    'chapters' => $chapters
                ]);
            }
        }
    }

    public function show($book, $chapterID)
    {
        $novels = json_decode(Storage::get('novels/novels.json'));
        foreach ($novels as $novel) {
            if ($novel->href == 'http://www.qu.la/book/' . $book . '/') {
                $currentNovel = $novel;
                break;
            }
        }
        $chapters = json_decode(Storage::get('novels/' . $currentNovel->title . '/chapters.json'));
        foreach ($chapters as $chapter) {
            if ($chapter->href == 'http://www.qu.la/book/' . $book . '/' . $chapterID . '.html') {
                $content = Storage::get('novels/' . $currentNovel->title . '/chapters/' . $chapter->chapter . '.txt');
                return view('novels.show', [
                    'chapter' => $chapter,
                    'content' => $content
                ]);
            }
        }
    }
}
