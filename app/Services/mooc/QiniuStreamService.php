<?php


namespace App\Services\mooc;


use Illuminate\Support\Facades\Log;
use Qiniu\Pili\Client;
use function Qiniu\Pili\HDLPlayURL;
use function Qiniu\Pili\HLSPlayURL;
use Qiniu\Pili\Mac;
use function Qiniu\Pili\RTMPPlayURL;
use function Qiniu\Pili\RTMPPublishURL;
use function Qiniu\Pili\SnapshotPlayURL;
use Qiniu\Pili\Stream;

class QiniuStreamService
{
    /**
     * @var \Illuminate\Config\Repository
     */
    private $domain;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $accessKey;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $secretKey;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $hubName;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \Qiniu\Pili\Hub
     */
    protected $hub;

    public function __construct()
    {
        $this->domain = config('stream.qiniu.domain');
        $this->accessKey = config('stream.qiniu.assessKey');
        $this->secretKey = config('stream.qiniu.secretKey');
        $this->hubName = config('stream.qiniu.hubName');

        $this->client = new Client(new Mac($this->accessKey, $this->secretKey));
        $this->hub = $this->client->hub($this->hubName);
    }

    /**
     * 创建直播流对象
     * @param string $streamKey
     * @return \Exception|\Qiniu\Pili\Stream|null
     * @author marhone
     */
    public function createStream(string $streamKey): ?Stream
    {
        try {
            $stream = $this->hub->create($streamKey);
        } catch (\Exception $exception) {
            Log::error("[STREAM] {$exception->getMessage()}");
            $stream = null;
        }

        return $stream;
    }

    /**
     * 保存直播回放并且返回链接
     * @param string $streamKey
     * @param int|null $start
     * @param int|null $end
     * @return string|null
     */
    public function saveStream(string $streamKey, ?int $start, ?int $end):?string
    {
        try {
            $stream = $this->hub->stream($streamKey);
            $fileName = $stream->save($start, $end);
        } catch(\Exception $e) {
            Log::error("[STREAM] {$e->getMessage()}");
            $fileName = null;
        }
        return $fileName;
    }
    /**
     * 获取直播流对象
     * @param string $streamKey
     * @return \Qiniu\Pili\Stream
     * @author marhone
     */
    public function stream(string $streamKey): Stream
    {
        return $this->hub->stream($streamKey);
    }

    /**
     * 推流地址
     * @param $streamKey
     * @param int $expireAfterSeconds
     * @return string
     * @author marhone
     */
    public function streamPublishUrl(string $streamKey, int $expireAfterSeconds = 7200): string
    {
        return RTMPPublishURL($this->domain, $this->hubName, $streamKey, $expireAfterSeconds, $this->accessKey, $this->secretKey);
    }

    /**
     * 直播地址
     * @param $streamKey
     * @return array
     * @author marhone
     */
    public function streamPlayUrls(string $streamKey): array
    {
        $rtmpDomain = config('stream.qiniu.playDomains.rtmp');
        $hlsDomain = config('stream.qiniu.playDomains.hls');
        $hdlDomain = config('stream.qiniu.playDomains.hdl');

        return [
            'rtmp' => RTMPPlayURL($rtmpDomain, $this->hubName, $streamKey),
            'hls' => HLSPlayURL($hlsDomain, $this->hubName, $streamKey),
            'hdl' => HDLPlayURL($hdlDomain, $this->hubName, $streamKey),
        ];
    }

    /**
     * 直播视频封面截图
     * @param $streamKey
     * @return string
     * @author marhone
     */
    public function snapshotImageUrl(string $streamKey): string
    {
        $snapDomain = config('stream.qiniu.snapshotDomain');
        return SnapshotPlayURL($snapDomain, $this->hubName, $streamKey);
    }

    /**
     * @param $seed
     * @return string
     * @author marhone
     */
    public function randStreamKeyById(string $seed): string
    {
        return uniqid($seed);
    }
}