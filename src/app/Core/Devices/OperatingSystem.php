<?php declare(strict_types=1);

namespace WbAssignment\Core\Devices;


enum OperatingSystem: string
{

	case Android = 'android';
	case iOS = 'iOS';
	case Lin = 'lin';
	case MacOS = 'macOS';
	case Win = 'win';

}
