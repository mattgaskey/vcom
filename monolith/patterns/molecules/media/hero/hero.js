(() => {
  'use strict';

  const qS = document.querySelector.bind(document);

  const setUpVideo = () => {
    if (!window.Vimeo) return;
  
    const heroVideo = qS('#hero-video');
    const play = qS('#hero-video-play');
    const pause = qS('#hero-video-pause');
    if (![heroVideo, play, pause].every(Boolean)) return;

    const { videoId: id } = heroVideo.dataset;
    if (!id) return;

    const autoplay = window.matchMedia && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const player = new window.Vimeo.Player(heroVideo, {
      id,
      autoplay,
      controls: false,
      muted: true,
      loop: true
    });

    // this triggers before autoplay starts,
    // so we make `play` button visible here always
    player.ready().then(() => {
      heroVideo.querySelector('iframe').tabIndex = -1;
      play.classList.remove('hidden');
    });

    let isFirstPlay = true;

    player.on('play', () => {
      if (isFirstPlay) {
        isFirstPlay = false;
        const image = qS('.image--hero');
        if (image) image.classList.add('opacity-0');
        const hero = qS('.hero');
        if (hero) hero.classList.add('with-video-background');
      }
      play.classList.add('hidden');
      pause.classList.remove('hidden');
    });

    player.on('pause', () => {
      play.classList.remove('hidden');
      pause.classList.add('hidden');
    });

    // adding/removing `.hidden` here looks redundant w/ handler above,
    // but it seems we do need these, too, to cover all cases
    play.addEventListener('click', ({ detail: clickCount }) => {
      player.play().then(() => {
        play.classList.add('hidden');
        pause.classList.remove('hidden');
        pause.focus();
        if (clickCount) pause.blur();
      });
    });
  
    pause.addEventListener('click', ({ detail: clickCount }) => {
      player.pause().then(() => {
        pause.classList.add('hidden');
        play.classList.remove('hidden');
        play.focus();
        if (clickCount) play.blur();
      });
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setUpVideo);
  } else {
    setUpVideo();
  }
})();