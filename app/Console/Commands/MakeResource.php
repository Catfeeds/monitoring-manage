<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produce:resource {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成Api资源文件';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');

        $filename = substr($path,strrpos($path,'/'));
        $dirname = substr($path,0,strrpos($path,'/'));
        if(!$dirname) {
            $dirname = "App/Api/Resources";
            $path = $dirname.'/'.$path;
        }
        $namespace = str_replace('/',"\\",$dirname);

        $res = "<?php\r\n\r\nnamespace $namespace;\r\n\r\nuse Illuminate\Http\Resources\Json\JsonResource;\r\n\r\n";
        $res .= "class $filename extends JsonResource\r\n{\r\n\t";
        $res .= "public function toArray(".'$request'.")\r\n\t{\r\n\t\treturn parent::toArray(".'$request'.");\r\n\t}\r\n}";

        if(!file_exists($path.'.php')) {
            if(!file_exists($dirname)) {
                mkdir($dirname, 0777,true);
            }
            file_put_contents($path.'.php',$res,FILE_APPEND);
            $this->info('Resource created successfully.');
        }
        else {
            $this->error('the file already exists.');
        }
    }
}
