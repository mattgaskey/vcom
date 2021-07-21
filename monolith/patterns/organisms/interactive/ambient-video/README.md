---
Title: Ambient Video
---

An ambient video with a play / pause control. This has some dependencies in the main javascript file. You will need to provide a video source and a video still to serve as the loading poster. If you have a Mac or a unix-style system that runs `ffmpeg` on the command line, you can extract the first frame with:

`ffmpeg -i [video file name] -vframes 1 -q:v 2 [output file name]`