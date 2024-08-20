# Overview

## Purpose of module
	This module was developed as part of a take-home assignment for a job application. Its primary purpose is to demonstrate proficiency in creating a Magento 2 API that performs date calculations.

Compatible with CE: 2.4.6
Stability: Stable Build


##Problems & Solutions
    Problem:
        Inconsistent date formats can lead to incorrect calculations and system errors.

    Solution:
        The module validates date inputs to ensure they follow the YYYY-MM-DDTHH:MM:SS format, preventing errors caused by incorrect formats.

    Problem:
        Users may need to calculate date differences in different ways, such as total days, weekdays, or weeks, and then convert these differences into various units.

    Solution:
        The API supports multiple calculation types (days, weekdays, weeks) and allows the results to be converted into units like seconds, minutes, hours, or years, providing flexibility for different use cases.

    Problem:
        Handling date differences across various time zones can be complex and error-prone.

    Solution:
        The module includes time zone support, allowing users to specify the time zone for calculations. If no time zone is provided, UTC is used by default, ensuring consistency.

    Problem:
        Invalid or unexpected inputs can lead to unreliable outputs or system failures.

    Solution:
        The module implements robust input validation for date formats, time zones, calculation types, and conversion units, ensuring that only valid data is processed.

    Problem:
        Lack of error handling can make it difficult to diagnose issues when calculations fail.

    Solution:
        The API includes detailed error handling, returning clear and actionable error messages when invalid inputs are provided or other issues arise, making it easier to troubleshoot and resolve problems. 


## Description 
	This module includes an API that calculates the difference between two dates in various formats. It supports multiple types of calculations and can convert the results into different units. The API also handles different time zones and validates inputs to ensure accurate and secure responses.

## Key Feature 
	* Calculates the difference between two dates with options to measure in days,    weekdays, or weeks.
    * Converts the calculated difference into various units such as seconds, minutes, hours, or years.
    * Supports calculations across different time zones, with UTC as the default.
    * Ensures secure and accurate results by validating date formats, time zones, calculation types, and conversion units.
    * Provides detailed error messages for invalid inputs or other issues during calculation, ensuring robustness and reliability.


## Future Enhancement 
	* None


# Deployment

## System requirements
    The Job_Assignment module does not have any specific system requirements.

## Install
    The Job_Assignment module is installed automatically (using the native Magento install mechanism) without any additional actions.
    Just keep the Job folder in app/code directory of magento project.


# Unit Test

## Attempted
    I wanted to let you know that I attempted to write the unit tests. However, as we discussed during the interview, I don’t have experience with unit testing, and it led to some issues. If you still need them, I can upload what I have so far.