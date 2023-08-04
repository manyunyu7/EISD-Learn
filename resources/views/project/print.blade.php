@php
header('Content-type: image/jpeg');
$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
$path = $storagePath."public/y.png";
$fontPath=$storagePath.'public/quicksand.ttf';
@endphp

@forelse ($data as $item)
    @php
    $class = $data->course_title;
    $name = $name;
    @endphp
@empty

@endforelse

@php
$font= $fontPath;
$image=imagecreatefrompng($path);
$color=imagecolorallocate($image, 51, 51, 102);
$color=imagecolorallocate($image, 0, 0, 0);
$date=date('d F, Y');
imagettftext($image, 28, 0, 60, 1100, $color,$font, $date);
// $name="Alan Suryajana";
$message="Atas Kelulusannya Pada Kelas : ";
imagettftext($image, 90, 0, 80, 670, $color,$font, $name);
imagettftext($image, 36, 0, 80, 760, $color,$font, $message);
imagettftext($image, 50, 0, 80, 860, $color,$font, $class);
// imagejpeg($image,"certificate/$name.jpg");
imagejpeg($image);
imagedestroy($image);
@endphp
