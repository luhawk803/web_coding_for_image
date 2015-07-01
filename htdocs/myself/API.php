<!DOCTYPE html5>
<html>
<head>

</head>
<body>
<h1 style="color: blue">Multisensory data synchronization API</h1>
<h5>Code design& implement: Hao Lu &nbsp Test: Seth Foster</h5>
<h3>Background</h3>
<p>This API is the served for <bold> <a href="http://www.indiana.edu/~dll/research.html">Embodied and situated Child-Parent Social Interaction Experiment.</a></bold> Data preprocessing. The raw data we collected, there is no time stamp, start and end at difference time. So this API will synchronize the all difference type of the data, and generate the final report for each experiment.</p>

<h3>Data source</h3>
<ul>
<li><a href="http://www.polhemus.com/?page=Motion_LIBERTY">Motion sensor data</a>. Capture the child and parent head and hands movement.</li>
<li>6 video camera. Capture the child and parent scene and eye video, and 2 camera from wall.</li>
<li>2 audio signal. From child and room mic.</li>
</ul>


<h3>Working flow</h3>
<p>Here is the general working flow of the API, </p>
<img border="0" src="API/workingflow.jpg" width="800" height="500">
<dl>
<dt>[<a href="API/create_dir.py">python create_dir.py</a> -s subj_ID]</dt>
<dd>- E.g. E:\python create_dir.py -s 3220</dd>
<dd>- This will move files from data collection computer  to processing computer</dd>
<dt>[<a href="API/synchronize_video.py">python synchronize_video.py</a> -s subj_ID]</dt>
<dd>- E.g. E:\python synchronize_video.py -s 3220</dd>
<dd>- This will synchronize video files based on timestamp information in raw_video_time.txt</dd>
<dt>[<a href="API/convert_video_to_mov.py">python convert_video_to_mov.py</a> -s subj_ID -v [video_list] -a [audio_list]]</dt>
<dd>- E.g. E:\python convert_video_to_mov.py -s 3220 -v [1,2,3,4,5,6] -a [1]</dd>
<dd>- This will create convert videos to .mov and place them into "after_conv" directory</dd>

<dt>[<a href="API/build_subject_dir.py">python build_subject_dir.py </a>-s subj_ID -c [cam_list]]</dt>
<dd>- E.g. E:\python build_subject_dir.py -s 3220 -c [0 7 8 1 2 3 4 5 6]</dd>
<dd>- This means video file labeled "cam01" will go to "cam07_video_r" in the final 
directory</dd>
<dd>- This builds structure in E:\multisensory\ and moves files there</dd>

<dt>[<a href="API/extract_frames.py">python extract_frames.py</a> -s subj_ID -b [tb1,tb2,tb...] -e [te1,te2,te3,...] -x [extract_list] -r [replace_blue]]</dt>
<dd>- E.g. E:\python extract_frames.py -s 3220 -b [700,5580,8630,11735] -e [3590,8272,11409,15408] -x [1 2 3 4 5 6 7 8]</dd>
<dd>- -x is the list of cameras to extract from, and the default is [1 2 3 4 5 6 7]</dd>
<dd>- -r is a parameter that checks for blue frames and replaces them with copies of the previous frame, and this is the default action (1 is default, 0 skips this part)</dd>
<dd>- -This will update the speech and video timestamps in “_info.txt” as well as cut the audio file such that 0:00 in the audio file aligns with the frame that starts trial one.
</dd>
<dd>- Note that the first frame of the video will count starting at 1. Other programs such as 
QuickTime use 0-based frame counting.</dd>




</dl>


</body>
</html>
