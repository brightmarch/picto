APP_ROOT = File.expand_path('../../', File.dirname(__FILE__))
WORKERS = 4

WORKERS.times do |i|
  God.watch do |w|
      w.dir = "#{APP_ROOT}"
      w.name = "picto-#{i}"
      w.group = "picto"
      w.uid = "deploy"
      w.gid = "deploy"
      w.interval = 5.seconds
      w.log = "#{APP_ROOT}/log/resque.god.log"
      w.env = { "VERBOSE" => "0", "QUEUE" => "upload", "INTERVAL" => 5, "APP_INCLUDE" => "#{APP_ROOT}/vendor/autoload.php" }
      w.start = "/usr/local/bin/php #{APP_ROOT}/vendor/chrisboulton/php-resque/resque.php"

      w.keepalive
  end
end
