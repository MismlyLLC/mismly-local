<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/api/metric.proto

namespace GPBMetadata\Google\Api;

class Metric
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Label::initOnce();
        \GPBMetadata\Google\Api\LaunchStage::initOnce();
        \GPBMetadata\Google\Protobuf\Duration::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
google/api/metric.proto
google.apigoogle/api/launch_stage.protogoogle/protobuf/duration.proto"�
MetricDescriptor
name (	
type (	+
labels (2.google.api.LabelDescriptor<
metric_kind (2\'.google.api.MetricDescriptor.MetricKind:

value_type (2&.google.api.MetricDescriptor.ValueType
unit (	
description (	
display_name (	G
metadata
 (25.google.api.MetricDescriptor.MetricDescriptorMetadata-
launch_stage (2.google.api.LaunchStage 
monitored_resource_types (	�
MetricDescriptorMetadata1
launch_stage (2.google.api.LaunchStageB0
sample_period (2.google.protobuf.Duration/
ingest_delay (2.google.protobuf.Duration"O

MetricKind
METRIC_KIND_UNSPECIFIED 	
GAUGE	
DELTA

CUMULATIVE"q
	ValueType
VALUE_TYPE_UNSPECIFIED 
BOOL	
INT64

DOUBLE

STRING
DISTRIBUTION	
MONEY"u
Metric
type (	.
labels (2.google.api.Metric.LabelsEntry-
LabelsEntry
key (	
value (	:8B_
com.google.apiBMetricProtoPZ7google.golang.org/genproto/googleapis/api/metric;metric�GAPIbproto3'
        , true);

        static::$is_initialized = true;
    }
}

