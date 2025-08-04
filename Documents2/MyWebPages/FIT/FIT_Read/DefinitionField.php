<?php

class DefinitionField {

    const GLOBAL_MESSAGE_FIELD_NAMES = [
        0 => [// FILE_ID
            0 => 'type',
            1 => 'manufacturer',
            2 => 'product',
            3 => 'serial_number',
            4 => 'time_created',
            5 => 'number',
        ],
        2 => [// DEVICE SETTINGS
            0 => "active_time_zone",
            1 => "utc_offset",
            2 => "time_offset",
            4 => "time_mode",
            5 => "time_zone_offset",
            12 => "backlight_mode",
            36 => "activity_tracker_enabled",
            39 => "clock_time",
            40 => "pages_enabled",
            46 => "move_alert_enabled",
            47 => "date_mode",
            55 => "display_orientation",
            56 => "mounting_side",
            57 => "default_page",
            58 => "autosync_min_steps",
            59 => "autosync_min_time",
            80 => "lactate_threshold_autodetect_enabled",
            86 => "ble_auto_upload_enabled",
            89 => "auto_sync_frequency",
            90 => "auto_activity_detect",
            94 => "number_of_screens",
            95 => "smart_notification_display_orientation",
            134 => "tap_interface",
            174 => "tap_sensitivity",
        ],
        3 => [// USER PROFILE
            254 => "message_index",
            0 => "friendly_name",
            1 => "gender",
            2 => "age",
            3 => "height",
            4 => "weight",
            5 => "language",
            6 => "elev_setting",
            7 => "weight_setting",
            8 => "resting_heart_rate",
            9 => "default_max_running_heart_rate",
            10 => "default_max_biking_heart_rate",
            11 => "default_max_heart_rate",
            12 => "hr_setting",
            13 => "speed_setting",
            14 => "dist_setting",
            16 => "power_setting",
            17 => "activity_class",
            18 => "position_setting",
            21 => "temperature_setting",
            22 => "local_id",
            23 => "global_id",
            28 => "wake_time",
            29 => "sleep_time",
            30 => "height_setting",
            31 => "user_running_step_length",
            32 => "user_walking_step_length",
            47 => "depth_setting",
            49 => "dive_count",
        ],
        7 => [// ZONES TARGET
            1 => "max_heart_rate",
            2 => "threshold_heart_rate",
            3 => "functional_threshold_power",
            5 => "hr_calc_type",
            7 => "pwr_calc_type",
        ],
        12 => [// SPORT
            0 => "sport",
            1 => "sub_sport",
            3 => "name",
        ],
        18 => [// SESSION
            254 => "message_index",
            253 => 'timestamp',
            0 => "event",
            1 => "event_type",
            2 => "start_time",
            3 => "start_position_lat",
            4 => "start_position_long",
            5 => "sport",
            6 => "sub_sport",
            7 => "total_elapsed_time",
            8 => "total_timer_time",
            9 => "total_distance",
            10 => "total_cycles",
            11 => "total_calories",
            13 => "total_fat_calories",
            14 => "avg_speed",
            15 => "max_speed",
            16 => "avg_heart_rate",
            17 => "max_heart_rate",
            18 => "avg_cadence",
            19 => "max_cadence",
            20 => "avg_power",
            21 => "max_power",
            22 => "total_ascent",
            23 => "total_descent",
        ],
        19 => [// LAP
            254 => "message_index",
            253 => "timestamp",
            0 => "event",
            1 => "event_type",
            2 => "start_time",
            3 => "start_position_lat",
            4 => "start_position_long",
            5 => "end_position_lat",
            6 => "end_position_long",
            7 => "total_elapsed_time",
            8 => "total_timer_time",
            9 => "total_distance",
            10 => "total_cycles",
            11 => "total_calories",
            12 => "total_fat_calories",
            13 => "avg_speed",
            14 => "max_speed",
            15 => "avg_heart_rate",
            16 => "max_heart_rate",
            17 => "avg_cadence",
            18 => "max_cadence",
            19 => "avg_power",
            20 => "max_power",
            21 => "total_ascent",
            22 => "total_descent",
            23 => "intensity",
            24 => "lap_trigger",
            25 => "sport",
            26 => "event_group",
            32 => "num_lengths",
            33 => "normalized_power",
            34 => "left_right_balance",
            35 => "first_length_index",
            37 => "avg_stroke_distance",
            38 => "swim_stroke",
            39 => "sub_sport",
            40 => "num_active_lengths",
            41 => "total_work",
            42 => "avg_altitude",
            43 => "max_altitude",
            44 => "gps_accuracy",
            45 => "avg_grade",
            46 => "avg_pos_grade",
            47 => "avg_neg_grade",
            48 => "max_pos_grade",
            49 => "max_neg_grade",
            50 => "avg_temperature",
            51 => "max_temperature",
            52 => "total_moving_time",
            53 => "avg_pos_vertical_speed",
            54 => "avg_neg_vertical_speed",
            55 => "max_pos_vertical_speed",
            56 => "max_neg_vertical_speed",
            57 => "time_in_hr_zone",
            58 => "time_in_speed_zone",
            59 => "time_in_cadence_zone",
            60 => "time_in_power_zone",
            61 => "repetition_num",
            62 => "min_altitude",
            63 => "min_heart_rate",
            71 => "wkt_step_index",
            74 => "opponent_score",
            75 => "stroke_count",
            76 => "zone_count",
            77 => "avg_vertical_oscillation",
            78 => "avg_stance_time_percent",
            79 => "avg_stance_time",
            80 => "avg_fractional_cadence",
            81 => "max_fractional_cadence",
            82 => "total_fractional_cycles",
            83 => "player_score",
            84 => "avg_total_hemoglobin_conc",
            85 => "min_total_hemoglobin_conc",
            86 => "max_total_hemoglobin_conc",
            87 => "avg_saturated_hemoglobin_percent",
            88 => "min_saturated_hemoglobin_percent",
            89 => "max_saturated_hemoglobin_percent",
            91 => "avg_left_torque_effectiveness",
            92 => "avg_right_torque_effectiveness",
            93 => "avg_left_pedal_smoothness",
            94 => "avg_right_pedal_smoothness",
            95 => "avg_combined_pedal_smoothness",
            98 => "time_standing",
            99 => "stand_count",
            100 => "avg_left_pco",
            101 => "avg_right_pco",
            102 => "avg_left_power_phase",
            103 => "avg_left_power_phase_peak",
            104 => "avg_right_power_phase",
            105 => "avg_right_power_phase_peak",
            106 => "avg_power_position",
            107 => "max_power_position",
            108 => "avg_cadence_position",
            109 => "max_cadence_position",
            110 => "enhanced_avg_speed",
            111 => "enhanced_max_speed",
            112 => "enhanced_avg_altitude",
            113 => "enhanced_min_altitude",
            114 => "enhanced_max_altitude",
            115 => "avg_lev_motor_power",
            116 => "max_lev_motor_power",
            117 => "lev_battery_consumption",
            118 => "avg_vertical_ratio",
            119 => "avg_stance_time_balance",
            120 => "avg_step_length",
            121 => "avg_vam",
            122 => "avg_depth",
            123 => "max_depth",
            124 => "min_temperature",
            136 => "enhanced_avg_respiration_rate",
            137 => "enhanced_max_respiration_rate",
            147 => "avg_respiration_rate",
            148 => "max_respiration_rate",
            149 => "total_grit",
            150 => "total_flow",
            151 => "jump_count",
            153 => "avg_grit",
            154 => "avg_flow",
            156 => "total_fractional_ascent",
            157 => "total_fractional_descent",
            158 => "avg_core_temperature",
            159 => "min_core_temperature",
            160 => "max_core_temperature",
        ],
        23 => [// DEVICE INFO
            253 => "timestamp",
            0 => "device_index",
            1 => "device_type",
            2 => "manufacturer",
            3 => "serial_number",
            4 => "product",
            5 => "software_version",
            6 => "hardware_version",
            7 => "cum_operating_time",
            10 => "battery_voltage",
            11 => "battery_status",
            18 => "sensor_position",
            19 => "descriptor",
            20 => "ant_transmission_type",
            21 => "ant_device_number",
            22 => "ant_network",
            25 => "source_type",
            27 => "product_name",
            32 => "battery_level",
        ],
        49 => [// FILE CREATOR
            0 => 'software_version',
            1 => 'hardware_version',
        ],
        34 => [// ACTIVITY
            253 => "timestamp",
            0 => "total_timer_time",
            1 => "num_sessions",
            2 => "type",
            3 => "event",
            4 => "event_type",
            5 => "local_timestamp",
            6 => "event_group",
        ],
        20 => [// RECORD
            253 => "timestamp",
            0 => "position_lat",
            1 => "position_long",
            2 => "altitude",
            3 => "heart_rate",
            4 => "cadence",
            5 => "distance",
            6 => "speed",
            7 => "power",
            8 => "compressed_speed_distance",
            9 => "grade",
            10 => "resistance",
            11 => "time_from_course",
            12 => "cycle_length",
            13 => "temperature",
            17 => "speed_1s",
            18 => "cycles",
            19 => "total_cycles",
            28 => "compressed_accumulated_power",
            29 => "accumulated_power",
            30 => "left_right_balance",
            31 => "gps_accuracy",
            32 => "vertical_speed",
            33 => "calories",
            39 => "vertical_oscillation",
            40 => "stance_time_percent",
            41 => "stance_time",
            42 => "activity_type",
            43 => "left_torque_effectiveness",
            44 => "right_torque_effectiveness",
            45 => "left_pedal_smoothness",
            46 => "right_pedal_smoothness",
            47 => "combined_pedal_smoothness",
            48 => "time128",
            49 => "stroke_type",
            50 => "zone",
            51 => "ball_speed",
            52 => "cadence256",
            53 => "fractional_cadence",
            54 => "total_hemoglobin_conc",
            55 => "total_hemoglobin_conc_min",
            56 => "total_hemoglobin_conc_max",
            57 => "saturated_hemoglobin_percent",
            58 => "saturated_hemoglobin_percent_min",
            59 => "saturated_hemoglobin_percent_max",
            62 => "device_index",
            67 => "left_pco",
            68 => "right_pco",
            69 => "left_power_phase",
            70 => "left_power_phase_peak",
            71 => "right_power_phase",
            72 => "right_power_phase_peak",
            73 => "enhanced_speed",
            78 => "enhanced_altitude",
            81 => "battery_soc",
            82 => "motor_power",
            83 => "vertical_ratio",
            84 => "stance_time_balance",
            85 => "step_length",
            87 => "cycle_length16",
            91 => "absolute_pressure",
            92 => "depth",
            93 => "next_stop_depth",
            94 => "next_stop_time",
            95 => "time_to_surface",
            96 => "ndl_time",
            97 => "cns_load",
            98 => "n2_load",
            99 => "respiration_rate",
            108 => "enhanced_respiration_rate",
            114 => "grit",
            115 => "flow",
            116 => "current_stress",
            117 => "ebike_travel_range",
            118 => "ebike_battery_level",
            119 => "ebike_assist_mode",
            120 => "ebike_assist_level_percent",
            123 => "air_time_remaining",
            124 => "pressure_sac",
            125 => "volume_sac",
            126 => "rmv",
            127 => "ascent_rate",
            129 => "po2",
            139 => "core_temperature",
        ],
        21 => [// EVENT
            253 => 'timestamp',
            0 => 'event',
            1 => 'event_type',
            2 => 'data_16',
            3 => 'data',
            4 => 'event_group',
            14 => 'activity_type',
            15 => 'start_timestamp',
        ],
        26 => [// WORKOUT
            254 => "message_index",
            4 => "sport",
            5 => "capabilities",
            6 => "num_valid_steps",
            8 => "wkt_name",
            11 => "sub_sport",
            14 => "pool_length",
            15 => "pool_length_unit",
            17 => "wkt_description",
        ],
        27 => [// WORKOUT STEP
            254 => "message_index",
            0 => "wkt_step_name",
            1 => "duration_type",
            2 => "duration_value",
            3 => "target_type",
            4 => "target_value",
            5 => "custom_target_value_low",
            6 => "custom_target_value_high",
            7 => "intensity",
            8 => "notes",
            9 => "equipment",
            10 => "exercise_category",
            11 => "exercise_name",
            12 => "exercise_weight",
            13 => "weight_display_unit",
            19 => "secondary_target_type",
            20 => "secondary_target_value",
            21 => "secondary_custom_target_value_low",
            22 => "secondary_custom_target_value_high",
        ],
        160 => [// GPS_METADATA
            253 => "timestamp",
            0 => "timestamp_ms",
            1 => "position_lat",
            2 => "position_long",
            3 => "enhanced_altitude",
            4 => "enhanced_speed",
            5 => "heading",
        ],
        162 => [// TIMESTAMP_CORRELATION
            253 => 'timestamp',
            0 => 'fractional_timestamp',
            1 => 'system_timestamp',
            2 => 'fractional_system_timestamp',
            3 => 'local_timestamp',
            4 => 'timestamp_ms',
            5 => 'system_timestamp_ms'
        ],
        216 => [// TIME_IN_ZONE
            253 => "timestamp",
            0 => "reference_mesg",
            1 => "reference_index",
            2 => "time_in_hr_zone",
            3 => "time_in_speed_zone",
            4 => "time_in_cadence_zone",
            5 => "time_in_power_zone",
            6 => "hr_zone_high_boundary",
            7 => "speed_zone_high_boundary",
            8 => "cadence_zone_high_bondary",
            9 => "power_zone_high_boundary",
            10 => "hr_calc_type",
            11 => "max_heart_rate",
            12 => "resting_heart_rate",
            13 => "threshold_heart_rate",
            14 => "pwr_calc_type",
            15 => "functional_threshold_power",
        ]
    ];
    const ENDIAN_ABILITY_BIT = 7;
    const BASE_TYPE_NUMBER_MASK = 0b11111;
    //const ENDIAN_ABILITY = [
    //0 => 'not endian', 1 => 'is endian'];
    const ENUM = 0;
    const SINT8 = 1;
    const UINT8 = 2;
    const SINT16 = 3;
    const UINT16 = 4;
    const SINT32 = 5;
    const UINT32 = 6;
    const STRING = 7;
    const FLOAT32 = 8;
    const FLOAT64 = 9;
    const UINT8Z = 10;
    const UINT16Z = 11;
    const UINT32Z = 12;
    const BYTE = 13;
    const SINT64 = 14;
    const UINT64 = 15;
    const UINT64Z = 16;
    const BASE_TYPES = [
        self::ENUM => 'enum',
        self::SINT8 => 'sint8',
        self::UINT8 => 'uint8',
        self::SINT16 => 'sint16',
        self::UINT16 => 'uint16',
        self::SINT32 => 'sint32',
        self::UINT32 => 'uint32',
        self::STRING => 'string',
        self::FLOAT32 => 'float32',
        self::FLOAT64 => 'float64',
        self::UINT8Z => 'uint8z',
        self::UINT16Z => 'uint16z',
        self::UINT32Z => 'uint32z',
        self::BYTE => 'byte',
        self::SINT64 => 'sint64',
        self::UINT64 => 'uint64',
        self::UINT64Z => 'uint64z'
    ];

    var ?ParseFit $parser = null;
    var int $globalMessageNumber = 0;
    var int $definitionNumber = 0;
    var int $size = 0;
    var bool $endianAbility = false;
    var bool $bigEndian = false;
    var int $baseTypeNumber = 0;

    function __construct(ParseFit $parser, bool $architecture, int $globalMessageNumber, array $threeByteFieldDescriptor) {
        $this->definitionNumber = $threeByteFieldDescriptor[0];
        $this->size = $threeByteFieldDescriptor[1];
        $baseTypeByte = $threeByteFieldDescriptor[2];
        $this->parser = $parser;
        $this->endianAbility = ($baseTypeByte & (1 << self::ENDIAN_ABILITY_BIT)) > 0;
        $this->bigEndian = $this->endianAbility && $architecture == 1;
        $this->globalMessageNumber = $globalMessageNumber;
        $this->baseTypeNumber = ($baseTypeByte & self::BASE_TYPE_NUMBER_MASK);
    }

    function getFieldNo(): int {
        return $this->definitionNumber;
    }

    function getFieldName(): string {
        return isset(self::GLOBAL_MESSAGE_FIELD_NAMES[$this->globalMessageNumber][$this->definitionNumber]) ? self::GLOBAL_MESSAGE_FIELD_NAMES[$this->globalMessageNumber][$this->definitionNumber] : '?';
    }

    function getValue() {

        $bytes = $this->parser->getBytes($this->size);

        switch ($this->baseTypeNumber) {
// STRING
            case self::STRING:
                $map = array_map('chr', $bytes);
                return '"' . implode($map) . '"';

// BYTE ARRAY
            case self::BYTE:
                return $bytes;

// FLOATS
            case self::FLOAT32:
            case self::FLOAT64:
                return 'float';

// SIGNED INTEGERS OF VARIOUS SIZES
            case self::SINT8:
            case self::SINT16:
            case self::SINT32:
            case self::SINT64:
                return $this->parser->bytesToInt($bytes, ['signed' => true, 'bigEndian' => $this->bigEndian]);

// UNSIGNED INTEGERS OF VARIOUS SIZES
            case self::ENUM;
            case self::UINT8;
            case self::UINT16;
            case self::UINT32;
            case self::UINT64;
            case self::UINT8Z;
            case self::UINT16Z;
            case self::UINT32Z;
            case self::UINT16Z;
                return $this->parser->bytesToInt($bytes, ['signed' => false, 'bigEndian' => $this->bigEndian]);

            default:
                return $this->parser->bytesToInt($bytes, ['signed' => false, 'bigEndian' => $this->bigEndian]);
        }
    }
}
