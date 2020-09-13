<?php
namespace battlemc\jenkins\classes;

/**
 * Class JenkinsJob
 * @package battlemc\jenkins\classes
 */
class JenkinsJob {
    /** @var string */
    private $server;
    /** @var JenkinsProfile */
    private $profile;
    /** @var string */
    private $job;
    /** @var string  */
    private $artifactName = "";
    /**
     * @return string
     */
    public function getJob(): string
    {
        return $this->job;
    }

    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * @param string $job
     */
    public function setJob(string $job): void
    {
        $this->job = $job;
    }

    /**
     * @param string $server
     */
    public function setServer(string $server): void
    {
        $this->server = $server;
    }

    /**
     * @return JenkinsProfile
     */
    public function getProfile(): JenkinsProfile
    {
        return $this->profile;
    }

    /**
     * @param JenkinsProfile $profile
     */
    public function setProfile(JenkinsProfile $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return string
     */
    public function getArtifactName(): string
    {
        return $this->artifactName;
    }

    /**
     * @param string $artifactName
     */
    public function setArtifactName(string $artifactName): void
    {
        $this->artifactName = $artifactName;
    }
}