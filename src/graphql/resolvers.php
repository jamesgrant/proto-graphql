<?php

$db = Database::get();

$roomByName = function ($name) use($db) {
    return $db->query('SELECT * FROM rooms WHERE name = ? LIMIT 1', [$name]);
};

$roomType = [
    'messages' => function ($room) use($db) {
        return $db->queryAll('SELECT * FROM messages WHERE roomId = ?', [$room]);
    },
];

$queryType = [
    'rooms' => function () use($db) {
        return $db->queryAll('SELECT * FROM rooms');
    },
    'messages' => function ($root, $args) use ($db, $roomByName) {
        $roomName = $args['roomName'];
        $room = $roomByName($roomName);
        return $db->queryAll('SELECT * FROM messages WHERE roomId = ?', [$room['id']]);
    },
];

$mutationType = [
    'start' => function ($root, $args) use ($db, $roomByName) {
        $roomName = $args['roomName'];
        $db->insert('INSERT INTO rooms (name) VALUES (?)', [$roomName]);
        return $roomByName($roomName);
    },
    'chat' => function ($root, $args) use ($db, $roomByName) {
        $roomName = $args['roomName'];
        $body = $args['body'];

        $room = $roomByName($roomName);

        $messageId = $db->insert('INSERT INTO messages (roomId, body, timestamp) VALUES (?, ?, ?)', [$room['id'], $body, time()]);
        return $db->query('SELECT * FROM messages WHERE id = ?', [$messageId]);
    },
];

return [
    'Room'     => $roomType,
    'Query'    => $queryType,
    'Mutation' => $mutationType,
];