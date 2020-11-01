<?php
namespace App\Console\Commands;

use File;
use App\CallRecording;
use Mail;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MySQLCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:mysql {--command= : <create|restore> command to execute} {--snapshot= : provide name of snapshot}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monthly MySQL Backup';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->option('command'))
        {
            case 'create':
                $this->takeSnapShot();
                break;

            case 'restore':
                $this->restoreSnapShot();
                break;

            default:
                $this->error("Invalid Option !!");
                break;
        }
    }

    /**
     * Function takes regular backup
     * for mysql database..
     *
     */
    private function takeSnapShot()
    {
        set_time_limit(0);

        //get date of last 3 month before  
        try{
            $date =  date("Y-m-d 00:00:00", strtotime('-3 days')); //months'));
            $fileName = './storage/app/public/call_logs_'.date("Y-m-d", strtotime('-3 months'));
            
            $process = new Process('mysqldump -u' .env('DB_USERNAME'). ' -p' .env('DB_PASSWORD'). ' --where="created_at<=\''.$date.'\'" ' .env('DB_DATABASE'). ' call_logs  > '.$fileName );
            $process->run();

            $delete = CallRecording::where('created_at', '<',$date)->delete();       
        
        }catch(\Exception $e){
            $this->info($e->getMessage());
        }
    }

    /**
     * Function restore given snapshot
     * for mysql database
     */
    private function restoreSnapShot()
    {
        $snapshot = $this->option('snapshot');
        if(!$snapshot) {
            $this->error("snapshot option is required.");
        }

        try {

            // get file from s3
            $s3 = \Storage::disk('s3');
            $found = $s3->get('/mysql/' .$snapshot. '.sql');
            $tempLocation = '/tmp/' .env('DB_DATABASE') . '_' . date("Y-m-d_Hi") . '.sql';

            // create a temp file
            $bytes_written = File::put($tempLocation, $found);
            if ($bytes_written === false) {
                $this->info("Error writing to file: " .$tempLocation);
            }

            // run the cli job
            $process = new Process("mysql -h " .env('DB_HOST'). " -u " .env('DB_USERNAME'). " -p" .env('DB_PASSWORD'). " ".env('DB_DATABASE'). " < {$tempLocation}");
            $process->run();

            //@unlink($tempLocation);
            if ($process->isSuccessful()) {
                $this->info("Restored snapshot: " .$snapshot);
            }
            else {
                throw new ProcessFailedException($process);
            }
        }
        catch (\Exception $e) {
            $this->info('File Not Found: '. $e->getMessage());
        }
    }
}