desc "Build binaries"
task :build do |task|
  system('composer install --no-dev --ignore-platform-reqs --prefer-dist --optimize-autoloader') or exit 1
end
