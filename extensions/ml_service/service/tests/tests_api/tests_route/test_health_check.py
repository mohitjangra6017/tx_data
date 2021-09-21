"""
This file is part of Totara Enterprise Extensions.

Copyright (C) 2021 onward Totara Learning Solutions LTD

Totara Enterprise Extensions is provided only to Totara
Learning Solutions LTD's customers and partners, pursuant to
the terms and conditions of a separate agreement with Totara
Learning Solutions LTD or its affiliate.

If you do not have an agreement with Totara Learning Solutions
LTD, you may not access, use, modify, or distribute this software.
Please contact [licensing@totaralearning.com] for more information.

@author Amjad Ali <amjad.ali@totaralearning.com>
@package ml_service
"""

import os
import time
import unittest
from unittest.mock import patch
from flask import current_app

from service.app import create_app
from service.tests.tests_api.tests_route.authentication_utils import AuthenticationUtils
from service.tests.util_objects import Elapsed


class TestHealthCheck(unittest.TestCase):
    """
    The test object to test units of the Health Checks Endpoint of the ML Service
    """

    def setUp(self) -> None:
        """
        Hook method for setting up the fixtures before exercising it
        """
        os.environ["FLASK_ENV"] = "testing"
        app = create_app()
        self.client = app.test_client()
        with app.app_context():
            secret_key = current_app.config.get("TOTARA_KEY")
            self.totara_url = current_app.config.get("TOTARA_URL")
            self.auth_info = current_app.config.get("auth_info", {})
        headers_producer = AuthenticationUtils(
            timestamp=time.time(), secret_key=secret_key
        )
        self.headers = headers_producer.create_headers()
        self.longMessage = False

    @patch(target="service.api.route.health_check.socket.gethostbyname")
    @patch(target="service.communicator.totara_graphql.TotaraGraphql.send")
    def test_http_status(self, mock_graphql, mock_hostname) -> None:
        """
        To test if the http status code is 200 when a legitimate request is made at
        /health-check
        """
        test_elapse = Elapsed()
        mock_graphql.return_value = (
            {"totara_webapi_status": {"status": "ok"}},
            test_elapse,
        )
        fake_test_ip = "0.0.0.0"
        mock_hostname.return_value = fake_test_ip
        test_response = self.client.get("/health-check", headers=self.headers)
        self.assertEqual(
            first=test_response.status_code,
            second=200,
            msg=(
                "The status code of the GET request at '/health-check' with "
                "valid data is not 200"
            ),
        )

    @patch(target="service.api.route.health_check.socket.gethostbyname")
    @patch(target="service.communicator.totara_graphql.TotaraGraphql.send")
    def test_health_check_content(self, mock_graphql, mock_hostname) -> None:
        """
        To test that the returned json object is as expected, does it have all the
        content that it is expected to return when a legitimate request is made at
        /health-check
        """
        test_elapse = Elapsed()
        fake_test_ip = "0.0.0.0"
        mock_graphql.return_value = (
            {"totara_webapi_status": {"status": "ok"}},
            test_elapse,
        )
        mock_hostname.return_value = fake_test_ip
        test_raw_response = self.client.get("/health-check", headers=self.headers)
        test_response = test_raw_response.get_json()

        totara_info = {
            "url": self.totara_url,
            "totara_ip": fake_test_ip,
            "elapsed_seconds": test_elapse.total_seconds(),
        }

        expected_response = {
            "success": True,
            "totara": {**totara_info, **self.auth_info},
        }

        self.assertIsInstance(
            obj=test_response,
            cls=dict,
            msg=(
                f"The returned object is of {type(test_response)} while it was "
                f"expected to be an instance of <class 'dict'>"
            ),
        )

        self.assertEqual(
            first=test_response["success"],
            second=expected_response["success"],
            msg=(
                "The 'success' value of the returned response is "
                f"{test_response['success']} while it was expected to be "
                f"{expected_response['success']}"
            ),
        )

        self.assertIsInstance(
            obj=test_response["totara"],
            cls=dict,
            msg=(
                "The 'totara' value of the returned response is "
                f"of type {type(test_response['totara'])} while it was expected to be "
                " an instance of <class 'dict'>"
            ),
        )

        self.assertDictEqual(
            d1=test_response["totara"],
            d2=expected_response["totara"],
            msg=(
                "The returned response from health check contains the dictionary "
                f"{test_response['totara']} while it was expected to be "
                f"{expected_response['totara']}"
            ),
        )
