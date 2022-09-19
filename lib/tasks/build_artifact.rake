desc "Create a debian package from the binaries."
task :build_artifact do |task|

  calver_version = ENV['PIPELINE_VERSION'].nil? ? Time.now.strftime("%Y.%m.%d.%H%M%S") : ENV['PIPELINE_VERSION']
  git_short_ref  = `git rev-parse --short HEAD`.strip
  version        = ENV['ARTIFACT_VERSION'].nil? ? "#{calver_version}+sha.#{git_short_ref}" : ENV['ARTIFACT_VERSION']
  artifact_name  = 'uitdatabank-movie-api-fetcher'
  vendor         = 'publiq VZW'
  maintainer     = 'Infra publiq <infra@publiq.be>'
  license        = 'Apache-2.0'
  description    = 'Silex backend for UiTDatabank 3 Movie API fetcher'
  source         = 'https://github.com/cultuurnet/movie-api-fetcher'

  FileUtils.mkdir_p('pkg')
  FileUtils.mkdir_p('log')
  FileUtils.touch('config.yml')

  system("fpm -s dir -t deb -n #{artifact_name} -v #{version} -a all -p pkg \
    -x '.git*' -x .travis.yml -x pkg -x config.dist.yml \
    -x 'Rakefile' -x 'Gemfile*' -x 'lib' -x 'vendor/bundle' -x '.bundle' -x 'Jenkinsfile' \
    -x .travis.yml -x pkg -x config.yml.dist \
    --prefix /var/www/movie-api-fetcher \
    --config-files /var/www/movie-api-fetcher/config.yml \
    --config-files /var/www/movie-api-fetcher/kinepolis_theaters.yml \
    --config-files /var/www/movie-api-fetcher/kinepolis_terms.yml \
    --deb-user www-data --deb-group www-data \
    --description '#{description}' --url '#{source}' --vendor '#{vendor}' \
    --license '#{license}' -m '#{maintainer}' \
    --deb-field 'Pipeline-Version: #{calver_version}' \
    --deb-field 'Git-Ref: #{git_short_ref}' \
    ."
  ) or exit 1
end
