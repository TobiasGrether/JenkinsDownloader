<?php

namespace battlemc\jenkins\task;

use battlemc\jenkins\classes\JenkinsJob;
use pocketmine\plugin\PharPluginLoader;
use pocketmine\Server;

class DownloaderTask extends \pocketmine\scheduler\AsyncTask
{

    public $jobs;
    public $profiles;
    public $path;

    public function __construct(array $jobs, array $profiles, string $path)
    {
        $this->jobs = serialize($jobs);
        $this->profiles = serialize($profiles);
        $this->path = $path;
    }

    public function onRun()
    {
        $jobs = unserialize($this->jobs);
        foreach ($jobs as $job) {
            if ($job instanceof JenkinsJob) {
                echo "[...] Downloading {$job->getJob()} / " . $job->getArtifactName() . " from  " . $job->getServer() . PHP_EOL;
                shell_exec("cd " . $this->path . " && curl -LJOs --user " . $job->getProfile()->getUsername() . ":" . $job->getProfile()->getToken() . " {$job->getServer()}/job/{$job->getJob()}/lastSuccessfulBuild/artifact/{$job->getArtifactName()}");
            }
        }
    }

    public function onCompletion(Server $server)
    {
        foreach (unserialize($this->jobs) as $job) {
            if ($job instanceof JenkinsJob) {
                if (file_exists($this->path . "/" . $job->getArtifactName())) {
                    if($this->canLoadPlugin($this->path . "/" . $job->getArtifactName())){
                        echo ("[...] Artifact found with file size of " . filesize($this->path . "/" . $job->getArtifactName()) . PHP_EOL);
                        echo ("[...] Searching for Artifact " . $job->getArtifactName() . PHP_EOL);
                        $plugin = $server->getPluginManager()->loadPlugin($this->path . "/" . $job->getArtifactName());
                        if ($plugin != null) {
                            $server->getPluginManager()->enablePlugin($plugin);
                            echo "[...] Plugin Artifact " . $job->getArtifactName() . " was enabled successfully" . PHP_EOL;
                        } else {
                            echo "[...] Plugin Artifact " . $job->getArtifactName() . " could not be enabled: Artifact missing!" . PHP_EOL;
                        }
                    }else{
                        echo ("[...] Wrong file extension, not phar!" . PHP_EOL);
                    }

                }else{
                    echo ("[...] File could not be found: " . $job->getArtifactName()) . PHP_EOL;
                }


            }
        }
    }
    public function canLoadPlugin(string $path) : bool{
        $ext = ".phar";
        return is_file($path) and substr($path, -strlen($ext)) === $ext;
    }
}