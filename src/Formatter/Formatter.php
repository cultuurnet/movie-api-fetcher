<?php

namespace CultuurNet\MovieApiFetcher\Formatter;

use ValueObjects\StringLiteral\StringLiteral;

class Formatter implements FormatterInterface
{

    /**
     * @inheritdoc
     */
    public function format($name, $type, $theme, $location)
    {
        return new StringLiteral(
            '{  "name": {    "nl": "' . $name .
               '"  },  "type": {    "id": "' . $type .
               '",    "label": "Film",    "domain": "eventtype"  },  "theme": {    "id": "' . $theme .
               '",    "label": "Actiefilm",    "domain": "theme"  },  "location": {    "id": "' . $location .
               '",    "name": "Kinepolis",    "address": {      "addressCountry": "BE",      "addressLocality": "sz",      "postalCode": "1000",      "streetAddress": "st 1"    }  }, "calendarType": "multiple", "timeSpans": [    {      "start": "2019-05-07T12:02:53+00:00",      "end": "2015-05-07T14:02:53+00:00"    },    {      "start": "2019-05-08T12:02:53+00:00",      "end": "2019-05-08T14:02:53+00:00"    },    {      "start": "2019-05-09T12:02:53+00:00",      "end": "2015-09-09T14:02:53+00:00"    }  ],  "startDate": "2019-05-07T12:02:53+00:00",  "endDate": "2019-05-09T14:02:53+00:00"}'
        );
    }
}
