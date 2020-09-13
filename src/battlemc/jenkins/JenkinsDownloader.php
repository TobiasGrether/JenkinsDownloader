<?php
namespace battlemc\jenkins;
use battlemc\jenkins\classes\JenkinsJob;
use battlemc\jenkins\classes\JenkinsProfile;
use battlemc\jenkins\task\DownloaderTask;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class JenkinsDownloader extends PluginBase
{
    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        if(!file_exists($this->getDataFolder() . "jobs.yml")) $this->saveResource("jobs.yml");
        if(!file_exists($this->getDataFolder() . "profiles.yml")) $this->saveResource("profiles.yml");
        $config = new Config($this->getDataFolder() . "profiles.yml", Config::YAML);
        $profiles = [];
        foreach($config->get("profiles") as $profileData){
            $profile = new JenkinsProfile();
            $profile->setUsername($profileData["username"]);
            $profile->setToken($profileData["token"]);
            $profiles[$profileData["id"]] = $profile;
        }
        $jobs = [];
        $config = new Config($this->getDataFolder() . "jobs.yml", Config::YAML);
        foreach($config->get("jobs") as $jobData){
            if(is_array($jobData)) {
                $job = new JenkinsJob();
                $job->setArtifactName($jobData["artifactName"]);
                $job->setProfile($profiles[$jobData["profileId"]]);
                $job->setServer($jobData["server"]);
                $job->setJob($jobData["job"]);
                $jobs[] = $job;
            }
        }
        $this->getServer()->getAsyncPool()->submitTask(new DownloaderTask($jobs, $profiles, Server::getInstance()->getDataPath() . "plugins/"));
    }
}