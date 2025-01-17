<?php declare(strict_types=1);

namespace App\Entity;

use App\Criticalmass\SocialNetwork\EntityInterface\SocialNetworkProfileAble;
use App\EntityInterface\PhotoInterface;
use App\EntityInterface\RouteableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Criticalmass\Router\Annotation as Routing;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user_user")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser implements SocialNetworkProfileAble, RouteableInterface, PhotoInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Groups({"timelapse"})
     * @JMS\Expose
     */
    protected $id;

    /**
     * @var string
     * @JMS\Groups({"timelapse"})
     * @JMS\Expose
     * @Assert\NotBlank()
     * @Routing\RouteParameter(name="username")
     */
    protected $username;

    /**
     * @ORM\OneToMany(targetEntity="Track", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $tracks;

    /**
     * @ORM\Column(type="smallint")
     * @JMS\Groups({"timelapse"})
     * @JMS\Expose
     */
    protected $colorRed = 0;

    /**
     * @ORM\Column(type="smallint")
     * @JMS\Groups({"timelapse"})
     * @JMS\Expose
     */
    protected $colorGreen = 0;

    /**
     * @ORM\Column(type="smallint")
     * @JMS\Groups({"timelapse"})
     * @JMS\Expose
     */
    protected $colorBlue = 0;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    protected $blurGalleries = false;

    /**
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="user")
     */
    protected $participations;

    /**
     * @ORM\OneToMany(targetEntity="BikerightVoucher", mappedBy="user")
     */
    protected $bikerightVouchers;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @ORM\Column(name="strava_id", type="string", length=255, nullable=true)
     */
    protected $stravaId;

    /**
     * @ORM\Column(name="strava_access_token", type="string", length=255, nullable=true)
     */
    protected $stravaAccessToken;

    /**
     * @ORM\Column(name="runkeeper_id", type="string", length=255, nullable=true)
     */
    protected $runkeeperId;

    /**
     * @ORM\Column(name="runkeeper_access_token", type="string", length=255, nullable=true)
     */
    protected $runkeeperAccessToken;

    /**
     * @ORM\Column(name="twitter_id", type="string", length=255, nullable=true)
     */
    protected $twitterId;

    /**
     * @ORM\Column(name="twitter_access_token", type="string", length=255, nullable=true)
     */
    protected $twitterkAccessToken;

    /**
     * @ORM\OneToMany(targetEntity="CityCycle", mappedBy="city", cascade={"persist", "remove"})
     */
    protected $cycles;

    /**
     * @var File $imageFile
     * @Vich\UploadableField(mapping="user_photo", fileNameProperty="imageName", size="imageSize", mimeType="imageMimeType")
     */
    protected $imageFile;

    /**
     * @var string $imageName
     * @JMS\Groups({"timelapse"})
     * @JMS\Expose
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @var int $imageSize
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $imageSize;

    /**
     * @var string $imageMimeType
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imageMimeType;

    /**
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    protected $ownProfilePhoto = false;

    public function __construct()
    {
        parent::__construct();

        $this->colorRed = rand(0, 255);
        $this->colorGreen = rand(0, 255);
        $this->colorBlue = rand(0, 255);

        $this->tracks = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->bikerightVouchers = new ArrayCollection();
    }

    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColorRed(): int
    {
        return $this->colorRed;
    }

    public function setColorRed(int $colorRed): User
    {
        $this->colorRed = $colorRed;

        return $this;
    }

    public function getColorGreen(): int
    {
        return $this->colorGreen;
    }

    public function setColorGreen(int $colorGreen): User
    {
        $this->colorGreen = $colorGreen;

        return $this;
    }

    public function getColorBlue(): int
    {
        return $this->colorBlue;
    }

    public function setColorBlue(int $colorBlue): User
    {
        $this->colorBlue = $colorBlue;

        return $this;
    }

    /**
     * @deprecated
     */
    public function equals(User $user): bool
    {
        return $user->getId() == $this->getId();
    }

    public function addTrack(Track $track): User
    {
        $this->tracks->add($track);

        return $this;
    }

    public function removeTrack(Track $tracks): User
    {
        $this->tracks->removeElement($tracks);

        return $this;
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): User
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): User
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    public function addParticipation(Participation $participation): User
    {
        $this->participations->add($participation);

        return $this;
    }

    public function removeParticipation(Participation $participation): User
    {
        $this->participations->removeElement($participation);

        return $this;
    }

    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function setStravaId(string $stravaId): User
    {
        $this->stravaId = $stravaId;

        return $this;
    }

    public function getStravaId(): ?string
    {
        return $this->stravaId;
    }

    public function setStravaAccessToken(string $stravaAccessToken): User
    {
        $this->stravaAccessToken = $stravaAccessToken;

        return $this;
    }

    public function getStravaAccessToken(): ?string
    {
        return $this->stravaAccessToken;
    }

    public function setFacebookId(string $facebookId): User
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookAccessToken(string $facebookAccessToken): User
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    public function getFacebookAccessToken(): ?string
    {
        return $this->facebookAccessToken;
    }

    public function setRunkeeperId(string $runkeeperId): User
    {
        $this->runkeeperId = $runkeeperId;

        return $this;
    }

    public function getRunkeeperId(): ?string
    {
        return $this->runkeeperId;
    }

    public function setRunkeeperAccessToken(string $runkeeperAccessToken): User
    {
        $this->runkeeperAccessToken = $runkeeperAccessToken;

        return $this;
    }

    public function getRunkeeperAccessToken(): ?string
    {
        return $this->runkeeperAccessToken;
    }

    public function setTwitterId(string $twitterId): User
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    public function getTwitterId(): ?string
    {
        return $this->twitterId;
    }

    public function setTwitterAccessToken(string $twitterkAccessToken): User
    {
        $this->twitterkAccessToken = $twitterkAccessToken;

        return $this;
    }

    public function getTwitterAccessToken(): ?string
    {
        return $this->twitterkAccessToken;
    }

    public function setBlurGalleries(bool $blurGalleries): User
    {
        $this->blurGalleries = $blurGalleries;

        return $this;
    }

    public function getBlurGalleries(): bool
    {
        return $this->blurGalleries;
    }

    public function isOauthAccount(): bool
    {
        return $this->runkeeperId || $this->stravaId || $this->facebookId || $this->isTwitterAccount();
    }

    public function isFacebookAccount(): bool
    {
        return $this->facebookId !== null;
    }

    public function isStravaAccount(): bool
    {
        return $this->stravaId !== null;
    }

    public function isRunkeeperAccount(): bool
    {
        return $this->facebookId !== null;
    }

    public function isTwitterAccount(): bool
    {
        return $this->twitterId !== null;
    }

    public function addBikerightVoucher(BikerightVoucher $bikerightVoucher): User
    {
        $this->bikerightVouchers->add($bikerightVoucher);

        return $this;
    }

    public function getBikerightVouchers(): Collection
    {
        return $this->bikerightVouchers;
    }

    public function removeBikerightVoucher(BikerightVoucher $bikerightVoucher): User
    {
        $this->bikerightVouchers->removeElement($bikerightVoucher);

        return $this;
    }

    public function addCycle(CityCycle $cityCycle): User
    {
        $this->cycles->add($cityCycle);

        return $this;
    }

    public function setCycles(Collection $cityCycles): User
    {
        $this->cycles = $cityCycles;

        return $this;
    }

    public function getCycles(): Collection
    {
        return $this->cycles;
    }

    public function removeCycle(CityCycle $cityCycle): User
    {
        $this->cycles->removeElement($cityCycle);

        return $this;
    }

    public function setImageFile(File $image = null): PhotoInterface
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(string $imageName = null): PhotoInterface
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(int $imageSize = null): PhotoInterface
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getImageMimeType(): ?string
    {
        return $this->imageMimeType;
    }

    public function setImageMimeType(string $imageMimeType = null): PhotoInterface
    {
        $this->imageMimeType = $imageMimeType;

        return $this;
    }

    public function hasOwnProfilePhoto(): bool
    {
        return $this->ownProfilePhoto;
    }

    public function setOwnProfilePhoto(bool $ownProfilePhoto): User
    {
        $this->ownProfilePhoto = $ownProfilePhoto;

        return $this;
    }
}
