<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    // ...
    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="categories")
     */
    private $posts;


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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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

    public function __toString()
    {
        if ($this->getName() == "") {
            $this->setName("Aucune catégorie");
        }

        return $this->name;
    }


        /**
         * Constructor
         */
        public
        function __construct()
        {
            $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        }


        /**
         * Add post
         *
         * @param \BlogBundle\Entity\Post $post
         *
         * @return Category
         */
        public
        function addPost(\BlogBundle\Entity\Post $post)
        {
            $this->posts[] = $post;

            return $this;
        }

        /**
         * Remove post
         *
         * @param \BlogBundle\Entity\Post $post
         */
        public
        function removePost(\BlogBundle\Entity\Post $post)
        {
            $this->posts->removeElement($post);
        }

        /**
         * Get posts
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public
        function getPosts()
        {
            return $this->posts;
        }
    }
