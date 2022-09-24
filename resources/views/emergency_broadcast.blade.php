@include('includes.imports.strings.broadcast_strings')

<!doctype html>

<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')

  {{-- Page Specific Stylesheet --}}
  <link rel="stylesheet" href="/css/chatbox.css">
  <link rel="stylesheet" href="{{ mix('css/emergency-broadcast.css') }}">

  <link href="//vjs.zencdn.net/7.10.2/video-js.min.css" rel="stylesheet">
  <script src="//vjs.zencdn.net/7.10.2/video.min.js"></script>

  {{--  Kakao Imports --}}
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
  <script type="text/javascript"
    src="https://dapi.kakao.com/v2/maps/sdk.js?appkey=fcbc674142c20da29ab5dfe6d1aae93f&libraries=services,clusterer,drawing">
  </script>

  {{-- SocketIO imports --}}
  <script src="https://cdn.socket.io/3.1.3/socket.io.min.js"
    integrity="sha384-cPwlPLvBTa3sKAgddT6krw0cJat7egBga3DJepJyrLl4Q9/5WLra3rrnMcyTyOnh"
    crossorigin="anonymous">
  </script>

  {{-- Emergency Broadcast Functionalities --}}
  <script src="/js/jwt.js" defer></script>
  <script src="/js/broadcast.js" defer></script>
</head>



<body id="htmlBody">
  <main id="main">
    <article id="container-top" v-cloak>
      <div id="contents">
        <article v-if="desktopMode" class="user-list">
          <div class="mt-3">
            <p class="user-type"> Guardians </p>
            <user-list-display :users="guardians"></user-list-display>
          </div>
        </article>
        <article class="media" id="mediaContainer">
          <video v-if="rtmpMode" id="hlsPlayer" class="video-js"
            controls></video>
          <canvas v-if="mjpegMode" id="mjpegView"></canvas>
          <div v-if="mjpegMode" class="frame-counter">
            (KOR)클릭하시면 비디오가 시작됩니다.<br>
            Format: MJPEG<br>
            Resolution: @{{ mjpegPlayer.getWidth() }} x @{{ mjpegPlayer.getHeight() }} <br>
            @{{ mjpegPlayer.frameCnt }} frames
          </div>
          <video v-if="mjpegMode" id="audioPlayer" class="video-js"
            controls></video>

          <article id="map"></article>
        </article>

        <article v-if="desktopMode" class="chat-container">
          <div class="chat-top-bar">
            <b class="title-live-chat">Live Chat</b>
          </div>
          <div id="text-area" class="chat-textarea">
            <div class="w-100 pl-2 pb-3" v-for="message in messages"
              :key="message.id">
              <span class="text-multiline text-light mr-1">
                <span class="text-success pr-2">
                    <strong>@{{ message.username }}</strong>
                </span>
                @{{ message.content }}
              </span>
            </div>
          </div>
          <div class="chat-inputarea">
            <form id="form">
              <input id="input" type="text" autocomplete="off"
                placeholder="Message"> <br>
              <button class="btn-send-chat no-outline"
                class="btn btn-outline-light" type="submit">send</button>
            </form>
          </div>
        </article>
      </div>

      <div class="marquee-background">
        <p class="marquee">
          {!! BroadcastStrings::$marqueeText !!}
        </p>
      </div>
    </article>
  </main>
</body>


</html>
