<?php

namespace OroCRM\Bundle\ZendeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;

use Oro\Bundle\IntegrationBundle\Model\IntegrationEntityTrait;
use OroCRM\Bundle\CaseBundle\Entity\CaseComment;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="orocrm_zd_comment",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unq_origin_id_channel_id", columns={"origin_id", "channel_id"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *  defaultValues={
 *      "entity"={
 *          "icon"="icon-list-alt"
 *      }
 *  }
 * )
 */
class TicketComment
{
    use IntegrationEntityTrait;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(name="origin_id", type="bigint", nullable=true)
     */
    protected $originId;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(name="html_body", type="text")
     */
    protected $htmlBody;

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean", options={"default"=false})
     */
    protected $public;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $author;

    /**
     * @var Ticket
     *
     * @ORM\ManyToOne(targetEntity="Ticket")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $ticket;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="origin_created_at", type="datetime", nullable=true)
     */
    protected $originCreatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var CaseComment
     *
     * @ORM\OneToOne(targetEntity="OroCRM\Bundle\CaseBundle\Entity\CaseComment", cascade={"persist"})
     * @ORM\JoinColumn(name="related_comment_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $relatedComment;

    /**
     * @var bool
     */
    private $updatedAtLocked = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOriginId()
    {
        return $this->originId;
    }

    /**
     * @param int $originId
     * @return TicketComment
     */
    public function setOriginId($originId)
    {
        $this->originId = $originId;
        return $this;
    }

    /**
     * @param string $body
     * @return TicketComment
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $htmlBody
     * @return TicketComment
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param boolean $public
     * @return TicketComment
     */
    public function setPublic($public)
    {
        $this->public = (bool)$public;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param User $author
     * @return TicketComment
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Ticket $ticket
     * @return TicketComment
     */
    public function setTicket(Ticket $ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param CaseComment $caseComment
     * @return TicketComment
     */
    public function setRelatedComment(CaseComment $caseComment = null)
    {
        $this->relatedComment = $caseComment;

        return $this;
    }

    /**
     * @return CaseComment
     */
    public function getRelatedComment()
    {
        return $this->relatedComment;
    }

    /**
     * @param \DateTime $createdAt
     * @return TicketComment
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $originCreatedAt
     * @return TicketComment
     */
    public function setOriginCreatedAt(\DateTime $originCreatedAt = null)
    {
        $this->originCreatedAt = $originCreatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOriginCreatedAt()
    {
        return $this->originCreatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return TicketComment
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        $this->updatedAtLocked = true;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt  = $this->createdAt ? $this->createdAt : new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = $this->updatedAt? $this->updatedAt : new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        if (!$this->updatedAtLocked) {
            $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
        }
    }
}
