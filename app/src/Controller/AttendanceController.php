<?php

namespace App\Controller;

use App\Entity\Promotions;
use App\Entity\Attendance;
use App\Enum\AttendanceType;
use App\Repository\PromotionsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AttendanceRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class AttendanceController extends AbstractController
{
    #[Route('/attendance', name: 'app_attendance')]
    public function index(PromotionsRepository $promotionsRepository): Response
    {
        $teacher = $this->getUser();

        if (!$teacher) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $teacherPromotions = $promotionsRepository->findBy(['professor' => $teacher]);

        return $this->render('attendance/index.html.twig', [
            'promotions' => $teacherPromotions,
        ]);
    }

    /* #[Route('/attendance/promo/{id}', name: 'app_attendance_sheet', methods: ['GET', 'POST'])]
    public function sheet(Promotions $promotion, UserRepository $userRepo, Request $request, EntityManagerInterface $em): Response
    {
        $students = $userRepo->findStudents(promotionId: $promotion->getId());

        if ($request->getMethod() === "POST") {
            $data = $request->getPayload()->all('attendance');
            foreach ($students as $student) {
                $status = $data[$student->getId()] ?? 'absent';

                $attendance = new Attendance();
                $attendance->setStudent($student)
                    ->setPromotion($promotion)
                    ->setDate(new \DateTime())
                    ->setStatus((AttendanceType::tryFrom($status) !== null) ? AttendanceType::tryFrom($status) : AttendanceType::ABSENT);

                $em->persist($attendance);
            }
            $em->flush();
            $this->addFlash('success', 'Appel enregistré !');
        }

        return $this->render('attendance/sheet.html.twig', [
            'promotion' => $promotion,
            'students' => $students
        ]);
    } */



    #[Route('/attendance/promo/{id}', name: 'app_attendance_sheet', methods: ['GET', 'POST'])]
    public function sheet(
        Promotions $promotion,
        UserRepository $userRepo,
        AttendanceRepository $attendanceRepo,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $students = $userRepo->findStudents(promotionId: $promotion->getId());

        $today = new \DateTime('today');
        $existingAttendances = $attendanceRepo->findTodayAttendanceByPromotion($promotion, $today);

        $attendanceMap = [];
        foreach ($existingAttendances as $attendance) {
            $attendanceMap[$attendance->getStudent()->getId()] = $attendance;
        }

        if ($request->getMethod() === "POST") {
            $data = $request->getPayload()->all('attendance');

            foreach ($students as $student) {
                $studentId = $student->getId();
                $status = $data[$studentId] ?? 'absent';

                if (isset($attendanceMap[$studentId])) {
                    $attendance = $attendanceMap[$studentId];
                } else {
                    $attendance = new Attendance();
                    $attendance->setStudent($student)
                        ->setPromotion($promotion)
                        ->setDate(new \DateTime());
                }

                $attendance->setStatus((AttendanceType::tryFrom($status) !== null) ? AttendanceType::tryFrom($status) : AttendanceType::ABSENT);

                $em->persist($attendance);
            }

            $em->flush();
            $this->addFlash('success', 'Appel enregistré !');

            return $this->redirectToRoute('app_attendance_sheet', ['id' => $promotion->getId()]);
        }

        return $this->render('attendance/sheet.html.twig', [
            'promotion' => $promotion,
            'students' => $students,
            'attendanceMap' => $attendanceMap,
            'date' => $today
        ]);
    }
}
