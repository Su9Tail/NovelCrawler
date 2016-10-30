<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use QL\QueryList;

class CrawlNovel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novel:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawling novels from web';

    /**
     * The URL of the cosole command
     *
     * @var string
     */
    protected $url = 'http://www.qu.la';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        set_time_limit(0);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $novels = $this->novels();
        $this->info('----------爬虫当前配置中共有' . count($novels) . '本小说----------');
        foreach ($novels as $novel) {
            $this->info('**********爬取《' . $novel->title . '》中**********');
            $chapters = $this->chapters($novel);
            $count = 0;
            foreach ($chapters as $chapter) {
                $temp = $this->crawl($novel, $chapter);
                $this->info('《' . $novel->title . '》【' . $chapter->chapter . '】已爬取');
                $count = $temp + $count;
                if ($count >= 10) {
                    exit('Succedd crawled 10 chapters\n');
                }
            }
        }
    }

    public function novels()
    {
        if (!Storage::exists('novels/novels.json')) {
            $rules = array(
                'title' => array('dl dt a', 'text'),
                'author' => array('dl dt span', 'text'),
                'href' => array('dl dt a', 'href', '', function ($content) {
                    return $this->url . $content;
                }),
                'image' => array('.image a img', 'src'),
                'description' => array('dl dd', 'text')
            );
            $range = '#hotcontent .l .item';
            $novels = QueryList::Query($this->url, $rules, $range, 'UTF-8', 'GB2312', true)->data;
            Storage::put('novels/novels.json', json_encode($novels));
        }
        return json_decode(Storage::get('novels/novels.json'));
    }

    public function chapters($novel)
    {
        if (!Storage::exists('novels/' . $novel->title . '/chapters.json')) {
            $url = $novel->href;
            $rules = [
                'chapter' => ['a', 'text'],
                'href' => ['a', 'href', '', function ($content) use ($url) {
                    return $url . $content;
                }]
            ];
            $range = '#list dl dd';
            $chapters = QueryList::Query($url, $rules, $range, 'UTF-8', 'GB2312', true)->data;
            Storage::put('novels/' . $novel->title . '/chapters.json', json_encode($chapters));
        }
        return json_decode(Storage::get('novels/' . $novel->title . '/chapters.json'));
    }

    public function crawl($novel, $chapter)
    {
        if (!Storage::exists('novels/' . $novel->title . '/chapters/' . $chapter->chapter . '.txt')) {
            $rules = [
                'title' => ['.bookname h1', 'text'],
                'content' => ['#content', 'html', '-script']
            ];
            $range = '.box_con';
            $content = QueryList::Query($chapter->href, $rules, $range, 'UTF-8', 'GB2312', true)->getData();
            Storage::put('novels/' . $novel->title . '/chapters/' . $chapter->chapter . '.txt', $content[0]['content']);
            return 1;
        }
        return 0;
    }
}
