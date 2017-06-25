<?php

namespace BlogBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment


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
     * @ORM\Column(name="content", type="text")
     */
    private $content;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    private $created;


    /**
     * Many Users have One Address.
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comment")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=false)
     */
    private $post;


    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="user_comments")
     * @ORM\JoinColumn(name="commentator_id", referencedColumnName="id")
     */
    private $commentator;


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
     * Set content
     *
     * @param string $content
     *
     * @return Comment
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


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setCreated()
    {
        $this->created = new \DateTime();
    }


    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }


    /**
     * Set post
     *
     * @param \BlogBundle\Entity\Post $post
     *
     * @return Comment
     */
    public function setPost(\BlogBundle\Entity\Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \BlogBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }


    /**
     * Set commentator
     *
     * @param \Application\Sonata\UserBundle\Entity\User $commentator
     *
     * @return Comment
     */
    public function setCommentator(\Application\Sonata\UserBundle\Entity\User $commentator = null)
    {

        $this->commentator = $commentator;

        return $this;
    }

    public function __toString()
    {
        if($this->getCommentator() != ""){
            return $this->getCommentator()->getUsername();

        }else{
            return "";
        }
    }

    /**
     * Get commentator
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getCommentator()
    {
        return $this->commentator;
    }
}
