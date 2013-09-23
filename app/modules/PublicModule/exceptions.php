<?php

namespace GeoCaching;

class RegisterException extends \Exception {}
class VerifyException extends RegisterException {}
class RegisterServerException extends RegisterException {}