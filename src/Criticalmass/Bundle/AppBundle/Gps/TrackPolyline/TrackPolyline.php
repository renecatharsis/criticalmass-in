<?php

namespace Criticalmass\Bundle\AppBundle\Gps\TrackPolyline;

use Criticalmass\Bundle\AppBundle\Entity\Track;
use Criticalmass\Bundle\AppBundle\Gps\GpxReader\TrackReader;

/**
 * @deprecated
 */
class TrackPolyline
{
    /**
     * @var Track $track
     */
    protected $track;

    protected $polyline = null;
    protected $xmlRootNode = null;
    protected $trackReader = null;
    protected $gapWidth = null;

    public function __construct(TrackReader $trackReader, $gapWidth)
    {
        $this->trackReader = $trackReader;
        $this->gapWidth = $gapWidth;
    }

    public function loadTrack(Track $track)
    {
        $this->track = $track;

        $this->trackReader->loadTrack($this->track);

        $this->xmlRootNode = $this->trackReader->getRootNode();

        return $this;
    }

    public function getPolyline()
    {
        return $this->polyline;
    }

    public function execute()
    {
        $start = $this->track->getStartPoint();
        $end = $this->track->getEndPoint();

        $list = $this->trackReader->slicePublicCoords();

        $polyline = \Polyline::Encode($list);

        $this->polyline = $polyline;
    }
} 
