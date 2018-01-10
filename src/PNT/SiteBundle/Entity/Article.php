<?php

namespace PNT\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="PNT\SiteBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rightAlign = false;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="lover", type="boolean", options={"default" : true})
     */
    private $visible;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255)
     */
    private $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="more_text", type="text", nullable=true)
     */
    private $moreText;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $image;

    /**
     * Collection of Publications
     * @ORM\ManyToMany(targetEntity="PNT\SiteBundle\Entity\Publication", cascade={"persist"})
     */
    private $publications;

    /**
     * @var bool
     *
     * @ORM\Column(name="right_align", type="boolean")
     */
    private $rightAlign;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set visible
     *
     * @param string $visible
     *
     * @return Article
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return string
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set domain
     *
     * @param string $domain
     *
     * @return Article
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Article
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set moreText
     *
     * @param string $moreText
     *
     * @return Article
     */
    public function setMoreText($moreText)
    {
        $this->moreText = $moreText;

        return $this;
    }

    /**
     * Get moreText
     *
     * @return string
     */
    public function getMoreText()
    {
        return $this->moreText;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add publication
     *
     * @param \PNT\SiteBundle\Entity\Publication $publication
     *
     * @return Selection
     */
    public function addPublication(\PNT\SiteBundle\Entity\Publication $publication)
    {
        $this->publications[] = $publication;

        return $this;
    }

    /**
     * Remove publication
     *
     * @param \PNT\SiteBundle\Entity\Publication $publication
     */
    public function removePublication(\PNT\SiteBundle\Entity\Publication $publication)
    {
        $this->publications->removeElement($publication);
    }

    /**
     * Get publications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Set rightAlign
     *
     * @param boolean $rightAlign
     *
     * @return Article
     */
    public function setRightAlign($rightAlign)
    {
        $this->rightAlign = $rightAlign;

        return $this;
    }

    /**
     * Get rightAlign
     *
     * @return bool
     */
    public function getRightAlign()
    {
        return $this->rightAlign;
    }
}
