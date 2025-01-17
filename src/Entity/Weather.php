<?php declare(strict_types=1);

namespace App\Entity;

use App\Criticalmass\Weather\EntityInterface\WeatherInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="weather")
 * @ORM\Entity(repositoryClass="App\Repository\WeatherRepository")
 */
class Weather implements WeatherInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ride", inversedBy="weather")
     * @ORM\JoinColumn(name="ride_id", referencedColumnName="id")
     */
    protected $ride;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $json;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $weatherDateTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $creationDateTime;

    /**
     * @ORM\Column(type="float")
     */
    protected $temperatureMin;

    /**
     * @ORM\Column(type="float")
     */
    protected $temperatureMax;

    /**
     * @ORM\Column(type="float")
     */
    protected $temperatureMorning;

    /**
     * @ORM\Column(type="float")
     */
    protected $temperatureDay;

    /**
     * @ORM\Column(type="float")
     */
    protected $temperatureEvening;

    /**
     * @ORM\Column(type="float")
     */
    protected $temperatureNight;

    /**
     * @ORM\Column(type="float")
     */
    protected $pressure;

    /**
     * @ORM\Column(type="float")
     */
    protected $humidity;

    /**
     * @ORM\Column(type="integer")
     */
    protected $weatherCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $weather;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $weatherDescription;

    /**
     * @ORM\Column(type="string", length=5)
     */
    protected $weatherIcon;

    /**
     * @ORM\Column(type="float")
     */
    protected $windSpeed;

    /**
     * @ORM\Column(type="float")
     */
    protected $windDirection;

    /**
     * @ORM\Column(type="float")
     */
    protected $clouds;

    /**
     * @ORM\Column(type="float")
     */
    protected $precipitation;

    public function __construct()
    {
        $this->creationDateTime = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJson(): ?string
    {
        return $this->json;
    }

    public function setJson(string $json = null): Weather
    {
        $this->json = $json;

        return $this;
    }

    public function getWeatherDateTime(): ?\DateTime
    {
        return $this->weatherDateTime;
    }

    public function setWeatherDateTime(\DateTime $weatherDateTime = null): WeatherInterface
    {
        $this->weatherDateTime = $weatherDateTime;

        return $this;
    }

    public function getCreationDateTime(): ?\DateTime
    {
        return $this->creationDateTime;
    }

    public function setCreationDateTime(\DateTime $creationDateTime = null): WeatherInterface
    {
        $this->creationDateTime = $creationDateTime;

        return $this;
    }

    public function getTemperatureMin(): ?float
    {
        return $this->temperatureMin;
    }

    public function setTemperatureMin(float $temperatureMin = null): WeatherInterface
    {
        $this->temperatureMin = $temperatureMin;

        return $this;
    }

    public function getTemperatureMax(): ?float
    {
        return $this->temperatureMax;
    }

    public function setTemperatureMax(float $temperatureMax = null): WeatherInterface
    {
        $this->temperatureMax = $temperatureMax;

        return $this;
    }

    public function getTemperatureMorning(): ?float
    {
        return $this->temperatureMorning;
    }

    public function setTemperatureMorning(float $temperatureMorning = null): WeatherInterface
    {
        $this->temperatureMorning = $temperatureMorning;

        return $this;
    }

    public function getTemperatureDay(): ?float
    {
        return $this->temperatureDay;
    }

    public function setTemperatureDay(float $temperatureDay = null): WeatherInterface
    {
        $this->temperatureDay = $temperatureDay;

        return $this;
    }

    public function getTemperatureEvening(): ?float
    {
        return $this->temperatureEvening;
    }

    public function setTemperatureEvening(float $temperatureEvening = null): WeatherInterface
    {
        $this->temperatureEvening = $temperatureEvening;

        return $this;
    }

    public function getTemperatureNight(): ?float
    {
        return $this->temperatureNight;
    }

    public function setTemperatureNight(float $temperatureNight = null): WeatherInterface
    {
        $this->temperatureNight = $temperatureNight;

        return $this;
    }

    public function getPressure(): ?float
    {
        return $this->pressure;
    }

    public function setPressure(float $pressure = null): WeatherInterface
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity = null): WeatherInterface
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getWeatherCode(): ?int
    {
        return $this->weatherCode;
    }

    public function setWeatherCode(int $weatherCode = null): WeatherInterface
    {
        $this->weatherCode = $weatherCode;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(string $weather = null): WeatherInterface
    {
        $this->weather = $weather;

        return $this;
    }

    public function getWeatherDescription(): ?string
    {
        return $this->weatherDescription;
    }

    public function setWeatherDescription(string $weatherDescription = null): WeatherInterface
    {
        $this->weatherDescription = $weatherDescription;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(float $windSpeed = null): WeatherInterface
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWindDirection(): ?float
    {
        return $this->windDirection;
    }

    public function setWindDirection(float $windDirection = null): WeatherInterface
    {
        $this->windDirection = $windDirection;

        return $this;
    }

    public function getClouds(): ?float
    {
        return $this->clouds;
    }

    public function setClouds(float $clouds = null): WeatherInterface
    {
        $this->clouds = $clouds;

        return $this;
    }

    public function getPrecipitation(): ?float
    {
        return $this->precipitation;
    }

    public function setPrecipitation(float $precipitation = null): WeatherInterface
    {
        $this->precipitation = $precipitation;

        return $this;
    }

    public function getRide(): ?Ride
    {
        return $this->ride;
    }

    public function setRide(Ride $ride = null): WeatherInterface
    {
        $this->ride = $ride;

        return $this;
    }

    public function getWeatherIcon(): ?string
    {
        return $this->weatherIcon;
    }

    public function setWeatherIcon(string $weatherIcon = null): WeatherInterface
    {
        $this->weatherIcon = $weatherIcon;

        return $this;
    }
}
