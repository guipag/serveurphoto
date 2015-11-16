<?php

namespace LT\CoreBundle\Entity;

/**
 * FormContact
 *
 */
class FormContact
{
    /**
     * @var string
     *
     */
    private $name;

    /**
     * @var string
     *
     */
    private $mail;

    /**
     * @var string
     *
     */
    private $object;

    /**
     * @var \DateTime
     *
     */
    private $time;

    /**
     * @var string
     *
     */
    private $content;

    public function __construct() {
        $this->datetime = new \Datetime();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return FormContact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return FormContact
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set object
     *
     * @param string $object
     * @return FormContact
     */
    public function setObject($object)
    {
        $this->object = $object;
    
        return $this;
    }

    /**
     * Get object
     *
     * @return string 
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return FormContact
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return FormContact
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
}
